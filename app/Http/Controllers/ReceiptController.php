<?php

namespace App\Http\Controllers;

use App\Models\MembershipTransaction;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    // Display a printable transaction receipt.
    public function show(
        MembershipTransaction $transaction
    ): View {
        // Load member information and payment records.
        $transaction->load([
            'member',
            'payments',
        ]);

        // Calculate the total amount paid.
        $totalPaid = $transaction->payments->sum('amount');

        // Calculate the remaining balance.
        $balance = max(
            0,
            $transaction->amount - $totalPaid
        );

        return view('receipts.show', compact(
            'transaction',
            'totalPaid',
            'balance'
        ));
    }
}
