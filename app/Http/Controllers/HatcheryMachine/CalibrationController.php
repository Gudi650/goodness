<?php

namespace App\Http\Controllers\HatcheryMachine;

use App\Http\Controllers\Controller;
use App\Models\MachineCalibration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CalibrationController extends Controller
{
    //
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'machine_id' => ['required', 'exists:machines,id'],
            'component' => ['required', 'string', 'max:150'],
            'calibration_date' => ['required', 'date'],
            'next_due' => ['required', 'date', 'after:calibration_date'],
            'performed_by_id' => ['nullable', 'exists:users,id'],
            'certificate' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('certificate')) {
            $data['certificate_path'] = $request->file('certificate')
                ->store('calibration-certificates', 'public');
        }

        MachineCalibration::create($data);

        return back()->with('success', 'Calibration record saved.');
    }
}
