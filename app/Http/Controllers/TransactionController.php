<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipLevel;
use App\Models\MembershipTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    // Display all transactions.
    public function index()
    {
        $transactions = MembershipTransaction::with('member')
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    // Show transaction creation form.
    public function create()
    {
        $members = Member::orderBy('name')->get();

        return view('transactions.create', compact('members'));
    }

    // Create a new membership or renewal transaction.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
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
                    return back()->withInput()->with(
                        'error',
                        'This member already has a membership transaction.'
                    );
                }
            }

            if ($type === 'Renewal') {
                $hasApproved = $member->membershipTransactions()
                    ->where('status', 'Approved')
                    ->exists();

                if (!$hasApproved) {
                    return back()->withInput()->with(
                        'error',
                        'This member must have an approved membership before renewal.'
                    );
                }

                $existingRenewal = $member->membershipTransactions()
                    ->where('transaction_type', 'Renewal')
                    ->whereIn('status', ['Pending', 'Paid'])
                    ->exists();

                if ($existingRenewal) {
                    return back()->withInput()->with(
                        'error',
                        'This member already has an unfinished renewal.'
                    );
                }
            }

            // Find the member's approved online application.
            $application = MembershipApplication::with('category')
                ->where('member_id', $member->id)
                ->where('status', 'approved')
                ->orderByDesc('id')
                ->first();

            $applicationId = null;

            if ($application) {
                if (!$application->category) {
                    return back()->withInput()->with(
                        'error',
                        'The application category is unavailable.'
                    );
                }

                // Online application fees come from categories.
                $amount = $application->category->fee;

                if ($type === 'New Membership') {
                    $applicationId = $application->id;

                    if (MembershipTransaction::where(
                        'membership_application_id',
                        $applicationId
                    )->exists()) {
                        return back()->withInput()->with(
                            'error',
                            'A transaction already exists for this application.'
                        );
                    }
                }
            } else {
                // Manual members use membership-level fees.
                $level = MembershipLevel::where(
                    'name',
                    $member->level
                )->first();

                if (!$level) {
                    return back()->withInput()->with(
                        'error',
                        'No valid membership level or approved application was found.'
                    );
                }

                $amount = $level->fee;
            }

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
                'membership_application_id' => $applicationId,
                'transaction_type' => $type,
                'reference_number' => $reference,
                'amount' => $amount,
                'status' => 'Pending',
            ]);

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction created successfully!');
        });
    }

    // Display transaction details and payments.
    public function show(MembershipTransaction $transaction)
    {
        $transaction->load(['member', 'payments']);

        $totalPaid = $transaction->payments->sum('amount');

        $balance = max(
            0,
            $transaction->amount - $totalPaid
        );

        return view('transactions.show', compact(
            'transaction',
            'totalPaid',
            'balance'
        ));
    }

    // Approve a fully paid transaction.
    public function approve(MembershipTransaction $transaction)
    {
        return DB::transaction(function () use ($transaction) {

            $transaction = MembershipTransaction::whereKey(
                $transaction->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaction->status !== 'Paid') {
                return back()->with(
                    'error',
                    'Only paid transactions can be approved.'
                );
            }

            $totalPaid = $transaction->payments()->sum('amount');

            if (
                round((float) $totalPaid, 2) <
                round((float) $transaction->amount, 2)
            ) {
                return back()->with(
                    'error',
                    'The transaction has not been fully paid.'
                );
            }

            $member = Member::whereKey($transaction->member_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Online application memberships use 12 months.
            
            // Check whether the member has an approved online application.
            $hasApprovedApplication = MembershipApplication::query()
                ->where('member_id', $member->id)
                ->where('status', 'approved')
                ->exists();

            if ($hasApprovedApplication) {
                // Online memberships have 12-month validity.
                $validityMonths = 12;
            } else {
                // Manual memberships use their assigned level.
                $level = MembershipLevel::where(
                    'name',
                    $member->level
                )->first();

                if (!$level) {
                    return back()->with(
                        'error',
                        'The membership level was not found.'
                    );
                }

                $validityMonths = $level->validity_months;
            }


            if (!$validityMonths || $validityMonths < 1) {
                return back()->with(
                    'error',
                    'The membership has no valid duration.'
                );
            }

            $startDate = now()->startOfDay();

            if ($transaction->transaction_type === 'Renewal') {
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

                if ($previousTransaction->end_date->gte($startDate)) {
                    $startDate = $previousTransaction->end_date
                        ->copy()
                        ->addDay();
                }
            }

            $endDate = $startDate->copy()
                ->addMonthsNoOverflow((int) $validityMonths)
                ->subDay();

            $transaction->update([
                'status' => 'Approved',
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            $member->update([
                'status' => 'Active',
            ]);

            return redirect()
                ->route('transactions.show', $transaction)
                ->with(
                    'success',
                    'Transaction approved successfully!'
                );
        });
    }

    // Cancel a pending transaction.
    
    // Cancel a pending transaction.
    public function cancel(MembershipTransaction $transaction)
    {
        return DB::transaction(function () use ($transaction) {

            // Lock the transaction during cancellation.
            $transaction = MembershipTransaction::whereKey(
                $transaction->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            // Only pending transactions can be cancelled.
            if ($transaction->status !== 'Pending') {
                return back()->with(
                    'error',
                    'Only pending transactions can be cancelled.'
                );
            }

            // Prevent cancellation if payments already exist.
            if ($transaction->payments()->exists()) {
                return back()->with(
                    'error',
                    'Transactions with recorded payments cannot be cancelled.'
                );
            }

            // Keep the transaction history, but release the
            // application link for a possible replacement.
            $transaction->update([
                'status' => 'Cancelled',
                'membership_application_id' => null,
            ]);

            return redirect()
                ->route('transactions.index')
                ->with(
                    'success',
                    'Transaction cancelled successfully!'
                );
        });
    }


    // Display transaction history for a member.
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
