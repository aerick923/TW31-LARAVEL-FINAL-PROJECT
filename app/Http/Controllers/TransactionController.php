<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipLevel;
use App\Models\MembershipTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    // Display all transactions
    public function index()
    {
        $transactions = MembershipTransaction::with('member')
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    // Show transaction creation form
    public function create()
    {
        $members = Member::orderBy('name')->get();

        return view('transactions.create', compact('members'));
    }

    // Create a new membership or renewal transaction
    
public function store(Request $request)
{
    $validated = $request->validate([
        'member_id' => [
            'required',
            'exists:members,id',
        ],
        'transaction_type' => [
            'required',
            Rule::in(['New Membership', 'Renewal']),
        ],
    ]);

    return DB::transaction(function () use ($validated) {

        $member = Member::whereKey($validated['member_id'])
            ->lockForUpdate()
            ->firstOrFail();

        $type = $validated['transaction_type'];

        if ($type === 'New Membership') {

            $existing = $member->membershipTransactions()
                ->where('transaction_type', 'New Membership')
                ->where('status', '!=', 'Cancelled')
                ->exists();

            if ($existing) {
                return back()
                    ->withInput()
                    ->with('error',
                        'This member already has a membership transaction.'
                    );
            }
        }

        if ($type === 'Renewal') {

            $hasApproved = $member->membershipTransactions()
                ->where('status', 'Approved')
                ->exists();

            if (!$hasApproved) {
                return back()
                    ->withInput()
                    ->with('error',
                        'This member must have an approved membership before renewal.'
                    );
            }

            $existingRenewal = $member->membershipTransactions()
                ->where('transaction_type', 'Renewal')
                ->whereIn('status', ['Pending', 'Paid'])
                ->exists();

            if ($existingRenewal) {
                return back()
                    ->withInput()
                    ->with('error',
                        'This member already has an unfinished renewal.'
                    );
            }
        }

        $level = MembershipLevel::where('name', $member->level)
            ->firstOrFail();

        do {
            $reference = 'TXN-' . strtoupper(Str::random(10));
        } while (
            MembershipTransaction::where(
                'reference_number',
                $reference
            )->exists()
        );

        MembershipTransaction::create([
            'member_id' => $member->id,
            'transaction_type' => $type,
            'reference_number' => $reference,
            'amount' => $level->fee,
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction created successfully!');
    });
}


    // Display transaction details and payments
    public function show(MembershipTransaction $transaction)
    {
        $transaction->load(['member', 'payments']);

        $totalPaid = $transaction->payments->sum('amount');

        $balance = max(0, $transaction->amount - $totalPaid);

        return view('transactions.show', compact(
            'transaction',
            'totalPaid',
            'balance'
        ));
    }

    // Approve a fully paid transaction
    
public function approve(MembershipTransaction $transaction)
{
    return DB::transaction(function () use ($transaction) {

        // Lock the transaction to prevent simultaneous approval.
        $transaction = MembershipTransaction::whereKey($transaction->id)
            ->lockForUpdate()
            ->firstOrFail();

        // Only paid transactions can be approved.
        if ($transaction->status !== 'Paid') {
            return back()->with(
                'error',
                'Only paid transactions can be approved.'
            );
        }

        // Confirm that the full amount has been paid.
        $totalPaid = $transaction->payments()->sum('amount');

        if (round((float) $totalPaid, 2) <
            round((float) $transaction->amount, 2)) {
            return back()->with(
                'error',
                'The transaction has not been fully paid.'
            );
        }

        // Retrieve the member's membership level.
        $member = $transaction->member;

        $level = MembershipLevel::where('name', $member->level)
            ->firstOrFail();

        $validityMonths = $level->validity_months;

        if (!$validityMonths || $validityMonths < 1) {
            return back()->with(
                'error',
                'The membership level has no valid duration.'
            );
        }

        // Default: membership begins on approval date.
        $startDate = now()->startOfDay();

        if ($transaction->transaction_type === 'Renewal') {

            // Find the latest approved membership expiration.
            $previousTransaction = $member->membershipTransactions()
                ->where('status', 'Approved')
                ->where('id', '!=', $transaction->id)
                ->whereNotNull('end_date')
                ->orderByDesc('end_date')
                ->first();

            if (!$previousTransaction) {
                return back()->with(
                    'error',
                    'No approved membership found for renewal.'
                );
            }

            // If membership is still valid, extend from expiration.
            if ($previousTransaction->end_date->gte($startDate)) {
                $startDate = $previousTransaction->end_date
                    ->copy()
                    ->addDay();
            }
        }

        // The final day of the membership period is inclusive.
        $endDate = $startDate->copy()
            ->addMonthsNoOverflow((int) $validityMonths)
            ->subDay();

        // Approve transaction and save membership validity.
        $transaction->update([
            'status' => 'Approved',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()
            ->route('transactions.show', $transaction)
            ->with(
                'success',
                'Transaction approved successfully!'
            );
    });
}


    // Cancel a pending transaction
    public function cancel(MembershipTransaction $transaction)
    {
        if ($transaction->status !== 'Pending') {
            return back()->with(
                'error',
                'Only pending transactions can be cancelled.'
            );
        }

        $transaction->update([
            'status' => 'Cancelled',
        ]);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction cancelled successfully!');
    }

    // Display transaction history for a member
    public function history(Member $member)
    {
        $transactions = $member->membershipTransactions()
            ->with('payments')
            ->latest()
            ->paginate(10);

        return view('transactions.history', compact(
            'member',
            'transactions'
        ));
    }
}
