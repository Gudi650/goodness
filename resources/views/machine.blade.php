<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Machine & Maintenance - Goodness Hatchery</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff8e5',
                            100: '#fde6a1',
                            500: '#f0b73a',
                            600: '#eaa106',
                            700: '#c88600'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, sans-serif; }
        h1, h2, h3, nav, button { font-family: Outfit, sans-serif; }
        .mono { font-family: ui-monospace, monospace; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-20 p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Machine & Maintenance</h1>
            <p class="text-sm text-slate-500">Incubators, hatchers, daily logs, alarms and maintenance/calibration schedules</p>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white border-b border-slate-200 -mx-6 px-6 mb-6">
            <div class="flex gap-8 overflow-x-auto">
                <button onclick="switchMmTab('machines', this)" class="mm-tab-btn py-4 text-sm font-medium text-slate-700 border-b-2 border-brand-600 cursor-pointer whitespace-nowrap">Machines</button>
                <button onclick="switchMmTab('logs', this)" class="mm-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Daily Logs</button>
                <button onclick="switchMmTab('alarms', this)" class="mm-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Alarms</button>
                <button onclick="switchMmTab('maintenance', this)" class="mm-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Maintenance Schedule</button>
                <button onclick="switchMmTab('calibration', this)" class="mm-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">Calibration</button>
                <button onclick="switchMmTab('sensors', this)" class="mm-tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer whitespace-nowrap">IoT Sensors</button>
            </div>
        </div>

        <!-- Action Row -->
        <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">
            <h2 id="mmSectionTitle" class="text-lg font-semibold">Machines</h2>

            <div class="flex gap-6">
                {{--
                <button onclick="openAddMachineModal()"
                    class="inline-flex items-center gap-1 px-4 py-2 bg-brand-600 text-white rounded hover:bg-brand-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Machine
                </button>
                --}}
                <div id="mmActionButton" class="w-full lg:w-auto"></div>
            </div>
        </div>

        <!-- Content Container -->
        <div class="bg-white border border-slate-200 rounded-lg p-4">
            <div id="mmTabContent" class="space-y-6">

                {{-- ============================= MACHINES ============================= --}}
                <div id="mm-tab-machines" class="mm-tab-panel">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Machine Registry</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Location</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Capacity (eggs)</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Installed Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($machines ?? [] as $machine)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $machine['code'] }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $machine['name'] }}</td>
                                        <td class="px-4 py-3">{{ $machine['type'] }}</td>
                                        <td class="px-4 py-3">{{ $machine['location'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ number_format($machine['capacity']) }}</td>
                                        <td class="px-4 py-3">{{ $machine['installed_date'] }}</td>
                                        <td class="px-4 py-3">
                                            @if ($machine['status'] === 'Active')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">{{ $machine['status'] }}</span>
                                            @elseif($machine['status'] === 'Under Maintenance')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">{{ $machine['status'] }}</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">{{ $machine['status'] }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-6">
                                                <button type="button" onclick="toggleMachineDetails('{{ $machine['id'] }}')"
                                                    class="text-slate-600 hover:text-slate-800 transition-colors"
                                                    title="View machine details" aria-label="View machine details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </button>
                                                {{--
                                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors"
                                                    title="Edit machine" aria-label="Edit machine"
                                                    onclick="openEditMachineModal({{ $machine['id'] }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                                                    </svg>
                                                </button>
                                                --}}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="machine-details-{{ $machine['id'] }}" class="hidden bg-slate-50/70">
                                        <td colspan="8" class="px-4 py-4">
                                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Serial Number</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['serial_number'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Manufacturer</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['manufacturer'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Assigned Technician</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['technician'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">IoT Enabled</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ ($machine['iot_enabled'] ?? false) ? 'Yes' : 'No' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Last Maintenance</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['last_maintenance'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Next Calibration Due</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['next_calibration'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Created At</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['created_at'] ?? '-' }}</p>
                                                </div>
                                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Updated At</p>
                                                    <p class="mt-1 text-sm text-slate-700">{{ $machine['updated_at'] ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-3 text-center text-slate-500">No machines registered</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= DAILY LOGS ============================= --}}
                <div id="mm-tab-logs" class="mm-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Daily Machine Logs</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Shift</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Temp (°C)</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Humidity (%)</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Turning Count</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Recorded By</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Flag</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($machineLogs ?? [] as $log)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3">{{ $log['date'] }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $log['machine'] }}</td>
                                        <td class="px-4 py-3">{{ $log['shift'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ $log['temperature'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ $log['humidity'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ $log['turning_count'] }}</td>
                                        <td class="px-4 py-3">{{ $log['recorded_by'] }}</td>
                                        <td class="px-4 py-3">
                                            @if ($log['flag'] === 'Normal')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Normal</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">{{ $log['flag'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-3 text-center text-slate-500">No logs recorded for today</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= ALARMS ============================= --}}
                <div id="mm-tab-alarms" class="mm-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Alarms & Alerts</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Date/Time</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Alarm Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Severity</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Resolved By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($alarms ?? [] as $alarm)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $alarm['datetime'] }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $alarm['machine'] }}</td>
                                        <td class="px-4 py-3">{{ $alarm['type'] }}</td>
                                        <td class="px-4 py-3">
                                            @if ($alarm['severity'] === 'Critical')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Critical</span>
                                            @elseif($alarm['severity'] === 'Warning')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Warning</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 border border-slate-200">Info</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($alarm['status'] === 'Resolved')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Resolved</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Open</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">{{ $alarm['resolved_by'] ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No alarms recorded</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= MAINTENANCE SCHEDULE ============================= --}}
                <div id="mm-tab-maintenance" class="mm-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Scheduled Maintenance</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Maintenance Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Scheduled Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Frequency</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Assigned Technician</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($maintenanceSchedule ?? [] as $item)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 font-medium">{{ $item['machine'] }}</td>
                                        <td class="px-4 py-3">{{ $item['maintenance_type'] }}</td>
                                        <td class="px-4 py-3">{{ $item['scheduled_date'] }}</td>
                                        <td class="px-4 py-3">{{ $item['frequency'] }}</td>
                                        <td class="px-4 py-3">{{ $item['technician'] }}</td>
                                        <td class="px-4 py-3">
                                            @if ($item['status'] === 'Completed')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Completed</span>
                                            @elseif($item['status'] === 'Overdue')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Overdue</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">Upcoming</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No maintenance tasks scheduled</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= CALIBRATION ============================= --}}
                <div id="mm-tab-calibration" class="mm-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">Calibration Records</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Component</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Calibration Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Next Due</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Performed By</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Certificate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($calibrationRecords ?? [] as $record)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 font-medium">{{ $record['machine'] }}</td>
                                        <td class="px-4 py-3">{{ $record['component'] }}</td>
                                        <td class="px-4 py-3">{{ $record['calibration_date'] }}</td>
                                        <td class="px-4 py-3">{{ $record['next_due'] }}</td>
                                        <td class="px-4 py-3">{{ $record['performed_by'] }}</td>
                                        <td class="px-4 py-3">
                                            @if (!empty($record['certificate']))
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">On file</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-600 border border-slate-200">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No calibration records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================= IOT SENSORS ============================= --}}
                <div id="mm-tab-sensors" class="mm-tab-panel hidden">
                    <div class="bg-white border rounded shadow-sm">
                        <h2 class="text-lg font-semibold px-4 py-3 border-b">IoT Sensors (Optional Integration)</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Sensor ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Machine</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Type</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase">Last Reading</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Last Sync</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($iotSensors ?? [] as $sensor)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 mono text-xs text-slate-500">{{ $sensor['sensor_id'] }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $sensor['machine'] }}</td>
                                        <td class="px-4 py-3">{{ $sensor['type'] }}</td>
                                        <td class="px-4 py-3 text-right mono">{{ $sensor['last_reading'] }}</td>
                                        <td class="px-4 py-3">{{ $sensor['last_sync'] }}</td>
                                        <td class="px-4 py-3">
                                            @if ($sensor['status'] === 'Online')
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Online</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 border border-red-200">Offline</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">No IoT sensors connected</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- Modals to build alongside this page --}}
    {{--
    @include('maintenance.modals.add-machine')
    @include('maintenance.modals.add-log')
    @include('maintenance.modals.add-maintenance')
    @include('maintenance.modals.add-calibration')
    --}}

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')

    <script>
        const mmTabTitles = {
            machines: 'Machines',
            logs: 'Daily Logs',
            alarms: 'Alarms',
            maintenance: 'Maintenance Schedule',
            calibration: 'Calibration',
            sensors: 'IoT Sensors'
        };

        function switchMmTab(tab, btn) {
            document.querySelectorAll('.mm-tab-panel').forEach(panel => panel.classList.add('hidden'));
            document.getElementById(`mm-tab-${tab}`).classList.remove('hidden');

            document.querySelectorAll('.mm-tab-btn').forEach(b => {
                b.classList.remove('text-slate-700', 'border-b-2', 'border-brand-600');
                b.classList.add('text-slate-500');
            });
            btn.classList.remove('text-slate-500');
            btn.classList.add('text-slate-700', 'border-b-2', 'border-brand-600');

            document.getElementById('mmSectionTitle').textContent = mmTabTitles[tab];
        }

        function toggleMachineDetails(id) {
            const detailsRow = document.getElementById(`machine-details-${id}`);
            if (detailsRow) {
                detailsRow.classList.toggle('hidden');
            }
        }
    </script>

</body>

</html>