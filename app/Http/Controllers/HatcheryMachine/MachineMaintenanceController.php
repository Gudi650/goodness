<?php

namespace App\Http\Controllers\HatcheryMachine;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MachineAlarm;
use App\Models\MachineCalibration;
use App\Models\MachineIotSensor;
use App\Models\MachineLog;
use App\Models\MachineMaintenanceSchedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MachineMaintenanceController extends Controller
{
    //
    /**
     * Renders the tabbed Machine & Maintenance page (maintenance.index),
     * which in turn @includes each tab as its own subpage view.
     */
    public function index(): View
    {
        $machines = Machine::with('technician')
            ->orderBy('name')
            ->get();

        $machineLogs = MachineLog::with(['machine', 'recordedBy'])
            ->whereDate('log_date', today())
            ->latest('log_date')
            ->get();

        $alarms = MachineAlarm::with(['machine', 'resolvedBy'])
            ->latest('triggered_at')
            ->limit(50)
            ->get();

        $maintenanceSchedule = MachineMaintenanceSchedule::with(['machine', 'technician'])
            ->orderBy('scheduled_date')
            ->get();

        $calibrationRecords = MachineCalibration::with(['machine', 'performedBy'])
            ->orderByDesc('calibration_date')
            ->get();

        $iotSensors = MachineIotSensor::with('machine')
            ->orderBy('sensor_code')
            ->get();

        return view('machine', compact(
            'machines',
            'machineLogs',
            'alarms',
            'maintenanceSchedule',
            'calibrationRecords',
            'iotSensors',
        ));
    }
}
