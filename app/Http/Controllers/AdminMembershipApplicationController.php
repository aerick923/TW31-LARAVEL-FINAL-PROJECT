<?php

namespace App\Http\Controllers;

use App\Models\MembershipApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminMembershipApplicationController extends Controller
{
    // Display membership applications for administrators.
    public function index(Request $request): View
    {
        $query = MembershipApplication::query()
            ->with('category');

        // Allow administrators to filter by application status.
        if ($request->filled('status')) {
            $status = $request->validate([
                'status' => ['required', 'in:pending,approved,rejected'],
            ])['status'];

            $query->where('status', $status);
        }

        $applications = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.applications.index', [
            'applications' => $applications,
        ]);
    }


    // Approve or reject a pending membership application.
    public function updateStatus(
        Request $request,
        MembershipApplication $application
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        // Only pending applications can be reviewed.
        $updated = MembershipApplication::query()
            ->whereKey($application->id)
            ->where('status', 'pending')
            ->update([
                'status' => $validated['status'],
            ]);

        if ($updated === 0) {
            return redirect()
                ->route('admin.applications.index')
                ->with('error', 'Only pending applications can be reviewed.');
        }

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application status updated successfully!');
    }

}
