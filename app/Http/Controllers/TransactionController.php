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

            // Prevent duplicate new-membership transactions.
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

            // Validate renewal eligibility.
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

            $applicationId = null;
            $level = null;

            // New online memberships use the level
            // selected during the application.
            if ($type === 'New Membership') {

                $application = MembershipApplication::query()
                    ->with('membershipLevel')
                    ->where('member_id', $member->id)
                    ->where('status', 'approved')
                    ->latest('id')
                    ->first();

                if ($application) {

                    $level = $application->membershipLevel;

                    if (!$level) {
                        return back()->withInput()->with(
                            'error',
                            'The approved application has no valid membership level.'
                        );
                    }

                    $applicationId = $application->id;

                    // Prevent duplicate application transactions.
                    $existingApplicationTransaction =
                        MembershipTransaction::query()
                            ->where(
                                'membership_application_id',
                                $applicationId
                            )
                            ->exists();

                    if ($existingApplicationTransaction) {
                        return back()->withInput()->with(
                            'error',
                            'A transaction already exists for this application.'
                        );
                    }
                }
            }

            // Manual memberships and renewals use
            // the member's currently assigned level.
            if (!$level) {
                $level = MembershipLevel::query()
                    ->where('name', $member->level)
                    ->first();

                if (!$level) {
                    return back()->withInput()->with(
                        'error',
                        'The member has no valid membership level.'
                    );
                }
            }

            // All membership fees come from membership levels.
            if ($level->fee === null) {
                return back()->withInput()->with(
                    'error',
                    'The selected membership level has no configured fee.'
                );
            }

            $amount = $level->fee;

            // Generate a unique transaction reference.
            do {
                $reference = 'TXN-' .
                    strtoupper(Str::random(10));

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
                ->with(
                    'success',
                    'Transaction created successfully!'
                );
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

            // Determine validity from the transaction's
            // selected membership level.
            $level = null;

            if ($transaction->transaction_type === 'New Membership'
                && $transaction->membership_application_id) {

                $application = MembershipApplication::query()
                    ->with('membershipLevel')
                    ->find($transaction->membership_application_id);

                if ($application) {
                    $level = $application->membershipLevel;
                }

                if (!$level) {
                    return back()->with(
                        'error',
                        'The linked application has no valid membership level.'
                    );
                }
            }

            // Manual memberships and renewals use
            // the member's assigned membership level.
            if (!$level) {
                $level = MembershipLevel::query()
                    ->where('name', $member->level)
                    ->first();

                if (!$level) {
                    return back()->with(
                        'error',
                        'The membership level was not found.'
                    );
                }
            }

            $validityMonths = $level->validity_months;

            if (!$validityMonths || $validityMonths < 1) {
                return back()->with(
                    'error',
                    'The membership has no valid duration.'
                );
            }

            $startDate = now()->startOfDay();

            // Renewals begin after the previous
            // approved membership period.
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
    public function cancel(MembershipTransaction $transaction)
    {
        return DB::transaction(function () use ($transaction) {

            $transaction = MembershipTransaction::whereKey(
                $transaction->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaction->status !== 'Pending') {
                return back()->with(
                    'error',
                    'Only pending transactions can be cancelled.'
                );
            }

            if ($transaction->payments()->exists()) {
                return back()->with(
                    'error',
                    'Transactions with recorded payments cannot be cancelled.'
                );
            }

            // Preserve the transaction record.
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
