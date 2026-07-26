<?php

namespace App\Http\Controllers\HatcheryMachine;

use App\Http\Controllers\Controller;
use App\Models\MachineMaintenanceSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class MaintenanceScheduleController extends Controller
{
    //
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'machine_id' => ['required', 'exists:machines,id'],
            'maintenance_type' => ['required', 'string', 'max:150'],
            'scheduled_date' => ['required', 'date'],
            'frequency' => ['required', 'in:One-off,Weekly,Monthly,Quarterly,Annual'],
            'technician_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['status'] = now()->toDateString() > $data['scheduled_date'] ? 'Overdue' : 'Upcoming';

        MachineMaintenanceSchedule::create($data);

        return back()->with('success', 'Maintenance task scheduled.');
    }

    public function complete(Request $request, MachineMaintenanceSchedule $schedule): RedirectResponse
    {
        $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $schedule->update([
            'status' => 'Completed',
            'completed_at' => now()->toDateString(),
            'notes' => $request->input('notes', $schedule->notes),
        ]);

        return back()->with('success', 'Maintenance task marked complete.');
    }
}
