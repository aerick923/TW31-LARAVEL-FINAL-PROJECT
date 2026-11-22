<?php

namespace App\Http\Controllers;

use App\Models\MembershipTransaction;
use App\Models\MembershipApplication;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    // Administrator receipt.
    public function show(
        MembershipTransaction $transaction
    ): View {
        $transaction->load([
            'member',
            'payments',
        ]);

        $totalPaid = $transaction->payments->sum('amount');

        $balance = max(
            0,
            (float) $transaction->amount - (float) $totalPaid
        );

        return view('receipts.show', compact(
            'transaction',
            'totalPaid',
            'balance'
        ));
    }

    // Receipt accessible only to the transaction owner.
    public function showUserReceipt(
        MembershipTransaction $transaction
    ): View {

        // Verify that the transaction belongs to a member
        // linked to this user's approved application.
        $ownsTransaction = MembershipApplication::query()
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->where('member_id', $transaction->member_id)
            ->exists();

        // Do not reveal another user's receipt.
        abort_unless($ownsTransaction, 404);

        // Load the member and payment records.
        $transaction->load([
            'member',
            'payments',
        ]);

        $totalPaid = $transaction->payments->sum('amount');

        $balance = max(
            0,
            (float) $transaction->amount - (float) $totalPaid
        );

        return view('receipts.show', compact(
            'transaction',
            'totalPaid',
            'balance'
        ));
    }
}
