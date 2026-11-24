<?php

namespace App\Http\Controllers;

use App\Mail\MembershipPaymentReceipt;
use App\Models\MembershipTransaction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DISPLAY AND FILTER RECORDED PAYMENTS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'payment_method' => [
                'nullable',
                Rule::in(['Cash', 'GCash', 'Bank Transfer']),
            ],
            'date_from' => ['nullable', 'date'],
            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from',
            ],
        ]);

        $query = Payment::query()
            ->with(['membershipTransaction.member']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where(
                    'payment_reference',
                    'like',
                    "%{$search}%"
                )->orWhereHas(
                    'membershipTransaction',
                    function ($transactionQuery) use ($search) {
                        $transactionQuery
                            ->where(
                                'reference_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'member',
                                function ($memberQuery) use ($search) {
                                    $memberQuery
                                        ->where(
                                            'name',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'membership_number',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            );
                    }
                );
            });
        }

        if (!empty($filters['payment_method'])) {
            $query->where(
                'payment_method',
                $filters['payment_method']
            );
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate(
                'payment_date',
                '>=',
                $filters['date_from']
            );
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate(
                'payment_date',
                '<=',
                $filters['date_to']
            );
        }

        $payments = $query
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('payments.index', compact('payments'));
    }

    /*
    |--------------------------------------------------------------------------
    | DISPLAY PAYMENT RECORDING FORM
    |--------------------------------------------------------------------------
    */

    public function create(MembershipTransaction $transaction)
    {
        $transaction->load(['member', 'payments']);

        $totalPaid = $transaction->payments->sum('amount');

        $balance = round(
            max(
                0,
                (float) $transaction->amount - (float) $totalPaid
            ),
            2
        );

        return view('payments.create', compact(
            'transaction',
            'totalPaid',
            'balance'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | RECORD PAYMENT AND SEND EMAIL RECEIPT
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        MembershipTransaction $transaction
    ) {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'decimal:0,2',
                'gt:0',
            ],
            'payment_method' => [
                'required',
                Rule::in(['Cash', 'GCash', 'Bank Transfer']),
            ],
            'payment_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
        ]);

        // Save the payment and update its transaction atomically.
        $payment = DB::transaction(function () use (
            $transaction,
            $validated
        ) {
            $lockedTransaction = MembershipTransaction::query()
                ->whereKey($transaction->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedTransaction->status !== 'Pending') {
                throw ValidationException::withMessages([
                    'amount' =>
                        'Payments can only be recorded for pending transactions.',
                ]);
            }

            // Calculate using centavos to avoid rounding errors.
            $requiredCents = (int) round(
                (float) $lockedTransaction->amount * 100
            );

            $paidCents = (int) round(
                (float) $lockedTransaction
                    ->payments()
                    ->sum('amount') * 100
            );

            $newPaymentCents = (int) round(
                (float) $validated['amount'] * 100
            );

            $remainingCents = $requiredCents - $paidCents;

            if (
                $remainingCents <= 0 ||
                $newPaymentCents > $remainingCents
            ) {
                throw ValidationException::withMessages([
                    'amount' =>
                        'Payment exceeds the remaining balance.',
                ]);
            }

            // Generate a unique payment reference.
            do {
                $reference = 'PAY-' .
                    strtoupper(Str::random(10));
            } while (
                DB::table('payments')
                    ->where('payment_reference', $reference)
                    ->exists()
            );

            // Create the payment record.
            $payment = $lockedTransaction->payments()->create([
                'payment_reference' => $reference,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $validated['payment_date'],
            ]);

            // Mark the transaction Paid when fully settled.
            if (
                $paidCents + $newPaymentCents === $requiredCents
            ) {
                $lockedTransaction->update([
                    'status' => 'Paid',
                ]);
            }

            return $payment;
        });

        /*
        |--------------------------------------------------------------------------
        | EMAIL THE PAYMENT RECEIPT
        |--------------------------------------------------------------------------
        |
        | The database transaction has already committed.
        | An email failure must not undo the recorded payment.
        |
        */

        $payment->load('membershipTransaction.member');

        $member = $payment->membershipTransaction?->member;

        $emailStatus = null;

        if ($member && filter_var(
            $member->email,
            FILTER_VALIDATE_EMAIL
        )) {
            try {
                Mail::to($member->email)->send(
                    new MembershipPaymentReceipt($payment)
                );

                $emailStatus = 'sent';
            } catch (\Throwable $exception) {
                Log::error('Payment receipt email failed.', [
                    'payment_id' => $payment->id,
                    'transaction_id' =>
                        $payment->membership_transaction_id,
                    'error' => $exception->getMessage(),
                ]);

                $emailStatus = 'failed';
            }
        } else {
            Log::warning(
                'Payment receipt email skipped: missing or invalid member email.',
                [
                    'payment_id' => $payment->id,
                    'transaction_id' =>
                        $payment->membership_transaction_id,
                ]
            );

            $emailStatus = 'skipped';
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO TRANSACTION DETAILS
        |--------------------------------------------------------------------------
        */

        $message = 'Payment recorded successfully!';

        if ($emailStatus === 'sent') {
            $message .= ' Email receipt sent successfully.';
        } elseif ($emailStatus === 'failed') {
            $message .= ' However, the email receipt could not be sent.';
        } else {
            $message .= ' No valid member email was available for the receipt.';
        }

        return redirect()
            ->route(
                'transactions.show',
                $transaction
            )
            ->with('success', $message);
    }
}
