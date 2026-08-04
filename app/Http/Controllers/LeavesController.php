<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Services\AccessControlService;
use Illuminate\Http\Request;

class LeavesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = Leave::with('user', 'approver')->orderBy('created_at', 'desc')->get();
        return response()->json($leaves);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|string|in:Annual Leave,Sick Leave,Maternity Leave,Paternity Leave,Compassionate Leave,Unpaid Leave,Other',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:500',
        ]);

        $leave = Leave::create([
            'user_id' => auth()->id(),
            'leave_type' => $validated['leave_type'],
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'],
            'days' => $validated['days'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'Pending',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Leave request submitted successfully.',
                'leave_id' => $leave->id,
                'leave' => $leave,
            ]);
        }

        return redirect()->back()->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        return response()->json($leave->load('user', 'approver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        $user = auth()->user();
        $access = app(AccessControlService::class);

        if (! $access->restrictHrmAccess($user)) {
            return redirect()->back()->with('error', 'You do not have permission to update leave requests.');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:Approved,Rejected',
        ]);

        $leave->update([
            'status' => $validated['status'],
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave request ' . strtolower($validated['status']) . ' successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        return response()->json($leave);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $leaveId = $leave->id;
        $leave->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'Leave request deleted successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Leave request deleted successfully.');
    }


    /**
     * Approve the leave requests for the authenticated user.
     * Only the HR can approve the leave requests.
     */
    protected function approveLeave(Request $request, Leave $leave)
    {

        dd("Approve Leave Function Called");

        //get authenticated user
        $user = auth()->user();

        //import the HR manager from the service file
        $isHR = app(AccessControlService::class)->isHrManager($user);

        // Check if the authenticated user is HR
        if (!$isHR) {
            return redirect()->route('dashboard')->with('error', 'You do not have access to the HRM page.');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:Approved,Rejected',
            'approval_remarks' => 'nullable|string|max:500',
        ]);

        $leave->update([
            'status' => $validated['status'],
            'approval_remarks' => $validated['approval_remarks'] ?? null,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave request has been ' . strtolower($validated['status']) . '.');
    }

}
