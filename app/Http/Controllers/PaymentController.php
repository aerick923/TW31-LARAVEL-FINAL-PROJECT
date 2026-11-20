<?php

namespace App\Http\Controllers;

use App\Models\MembershipTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    // Show the payment recording form.
    public function create(MembershipTransaction $transaction)
    {
        $transaction->load(['member', 'payments']);

        $totalPaid = $transaction->payments->sum('amount');

        $balance = round(
            max(0, (float) $transaction->amount - (float) $totalPaid),
            2
        );

        return view('payments.create', compact(
            'transaction',
            'totalPaid',
            'balance'
        ));
    }

    // Record a payment for a membership transaction.
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
                    'amount' => 'Payments can only be recorded for pending transactions.',
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
                    'amount' => 'Payment exceeds the remaining balance.',
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
