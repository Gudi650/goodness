<?php

namespace App\Http\Controllers\HatcheryMachine;

use App\Http\Controllers\Controller;
use App\Models\MachineAlarm;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class MachineAlarmController extends Controller
{
    //
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'machine_id' => ['required', 'exists:machines,id'],
            'alarm_type' => ['required', 'string', 'max:100'],
            'severity' => ['required', 'in:Critical,Warning,Info'],
            'triggered_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['status'] = 'Open';

        MachineAlarm::create($data);

        return back()->with('success', 'Alarm logged.');
    }

    public function resolve(Request $request, MachineAlarm $alarm): RedirectResponse
    {
        $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $alarm->update([
            'status' => 'Resolved',
            'resolved_by_id' => $request->user()?->id,
            'resolved_at' => now(),
            'notes' => $request->input('notes', $alarm->notes),
        ]);

        return back()->with('success', 'Alarm marked as resolved.');
    }
}
