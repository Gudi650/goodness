<?php

namespace App\Http\Controllers\HatcheryMachine;

use App\Http\Controllers\Controller;
use App\Models\MachineIotSensor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class IotSensorController extends Controller
{
    //
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'machine_id' => ['required', 'exists:machines,id'],
            'sensor_code' => ['required', 'string', 'max:100', 'unique:machine_iot_sensors,sensor_code'],
            'type' => ['required', 'string', 'max:50'],
        ]);

        $data['status'] = 'Offline';

        MachineIotSensor::create($data);

        return back()->with('success', 'Sensor registered.');
    }

    /**
     * Webhook-style ingestion endpoint for physical sensors to push readings.
     * Intended to sit behind routes/api.php with a sensor API token, not
     * the web session guard — see routes file for the suggested grouping.
     */
    public function ingestReading(Request $request, string $sensorCode): JsonResponse
    {
        $data = $request->validate([
            'value' => ['required', 'numeric'],
            'unit' => ['required', 'string', 'max:10'],
        ]);

        $sensor = MachineIotSensor::where('sensor_code', $sensorCode)->firstOrFail();
        $sensor->recordReading($data['value'], $data['unit']);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Flip any sensor that hasn't synced in the last 15 minutes to Offline.
     * Wire this into the console kernel schedule to run every few minutes.
     */
    public function markStaleSensorsOffline(): void
    {
        MachineIotSensor::where('status', 'Online')
            ->where('last_sync_at', '<', now()->subMinutes(15))
            ->update(['status' => 'Offline']);
    }
}
