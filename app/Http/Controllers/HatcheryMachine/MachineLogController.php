<?php

namespace App\Http\Controllers\HatcheryMachine;

use App\Http\Controllers\Controller;
use App\Models\MachineLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class MachineLogController extends Controller
{
    //
    // Machines run these two setter-machine safe ranges; outside this the log auto-flags.
    private const TEMP_MIN = 37.2;
    private const TEMP_MAX = 37.8;
    private const HUMIDITY_MIN = 50.0;
    private const HUMIDITY_MAX = 65.0;

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'machine_id' => ['required', 'exists:machines,id'],
            'log_date' => ['required', 'date'],
            'shift' => ['required', 'in:Morning,Afternoon,Night'],
            'temperature' => ['required', 'numeric', 'between:0,60'],
            'humidity' => ['required', 'numeric', 'between:0,100'],
            'turning_count' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['recorded_by_id'] = $request->user()?->id;
        $data['flag'] = $this->resolveFlag($data['temperature'], $data['humidity']);

        MachineLog::create($data);

        return back()->with('success', 'Log entry recorded.');
    }

    private function resolveFlag(float $temperature, float $humidity): string
    {
        if ($temperature < self::TEMP_MIN || $temperature > self::TEMP_MAX) {
            return 'Temp Out of Range';
        }

        if ($humidity < self::HUMIDITY_MIN || $humidity > self::HUMIDITY_MAX) {
            return 'Humidity Out of Range';
        }

        return 'Normal';
    }
}
