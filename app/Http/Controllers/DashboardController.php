<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipTransaction;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Count all registered members.
        $totalMembers = Member::count();

        // Count active members.
        $activeMembers = Member::where('status', 'Active')->count();

        // Count transactions awaiting payment or approval.
        $pendingTransactions = MembershipTransaction::whereIn(
            'status',
            ['Pending', 'Paid']
        )->count();

        // Calculate total recorded payments.
        $totalPayments = Payment::sum('amount');

        // Retrieve the five most recent transactions.
        $recentTransactions = MembershipTransaction::with('member')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalMembers',
            'activeMembers',
            'pendingTransactions',
            'totalPayments',
            'recentTransactions'
        ));
    }
}
