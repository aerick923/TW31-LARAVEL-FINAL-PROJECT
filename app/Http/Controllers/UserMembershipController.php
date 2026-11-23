<?php

namespace App\Http\Controllers;

use App\Models\MembershipApplication;
use App\Models\MembershipTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserMembershipController extends Controller
{
    // Retrieve the member linked to this user's
    // approved membership application.
    private function getMember()
    {
        $application = MembershipApplication::query()
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->whereNotNull('member_id')
            ->latest()
            ->first();

        return $application?->member;
    }

    // Display the logged-in user's membership details.
    public function membership(): View
    {
        $member = $this->getMember();

        $latestTransaction = $member
            ? $member->membershipTransactions()
                ->where('status', 'Approved')
                ->whereNotNull('end_date')
                ->orderByDesc('end_date')
                ->first()
            : null;

        return view('user.membership', compact(
            'member',
            'latestTransaction'
        ));
    }

    // Display only this user's applications.
    public function applications(): View
    {
        $applications = MembershipApplication::query()
            ->where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->get();

        return view('user.applications', compact(
            'applications'
        ));
    }

    // Display only this user's membership transactions.
    public function transactions(): View
    {
        $member = $this->getMember();

        $transactions = $member
            ? MembershipTransaction::query()
                ->where('member_id', $member->id)
                ->with(['payments'])
                ->latest()
                ->get()
            : collect();

        return view('user.transactions', compact(
            'member',
            'transactions'
        ));
    }
}
