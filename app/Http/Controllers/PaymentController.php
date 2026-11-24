<?php

namespace App\Http\Controllers;

use App\Models\MembershipTransaction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        // Retrieve payments and their related transactions and members.
        $query = Payment::query()
            ->with([
                'membershipTransaction.member',
            ]);

        // Search payment references, transaction references,
        // membership numbers, and member names.
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                    ->orWhereHas(
                        'membershipTransaction',
                        function ($transactionQuery) use ($search) {
                            $transactionQuery
                                ->where('reference_number', 'like', "%{$search}%")
                                ->orWhereHas(
                                    'member',
                                    function ($memberQuery) use ($search) {
                                        $memberQuery
                                            ->where('name', 'like', "%{$search}%")
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

        // Filter by payment method.
        if (!empty($filters['payment_method'])) {
            $query->where(
                'payment_method',
                $filters['payment_method']
            );
        }

        // Filter by starting payment date.
        if (!empty($filters['date_from'])) {
            $query->whereDate(
                'payment_date',
                '>=',
                $filters['date_from']
            );
        }

        // Filter by ending payment date.
        if (!empty($filters['date_to'])) {
            $query->whereDate(
                'payment_date',
                '<=',
                $filters['date_to']
            );
        }

        // Retrieve the matching payment records.
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
    | RECORD A PAYMENT
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

        DB::transaction(function () use ($transaction, $validated) {

            // Lock the transaction while recording payment.
            $transaction = MembershipTransaction::whereKey(
                $transaction->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            // Only pending transactions can receive payments.
            if ($transaction->status !== 'Pending') {
                throw ValidationException::withMessages([
                    'amount' =>
                        'Payments can only be recorded for pending transactions.',
                ]);
            }

            // Calculate amounts in cents to avoid rounding errors.
            $requiredCents = (int) round(
                (float) $transaction->amount * 100
            );

            $paidCents = (int) round(
                (float) $transaction->payments()->sum('amount') * 100
            );

            $newPaymentCents = (int) round(
                (float) $validated['amount'] * 100
            );

            $remainingCents = $requiredCents - $paidCents;

            // Reject overpayments and invalid balances.
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
                $reference = 'PAY-' . strtoupper(Str::random(10));
            } while (
                DB::table('payments')
                    ->where('payment_reference', $reference)
                    ->exists()
            );

            // Record the payment through the relationship.
            $transaction->payments()->create([
                'payment_reference' => $reference,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $validated['payment_date'],
            ]);

            // Mark transaction Paid once the full fee is covered.
            if ($paidCents + $newPaymentCents === $requiredCents) {
                $transaction->update([
                    'status' => 'Paid',
                ]);
            }
        });

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', 'Payment recorded successfully!');
    }
}
