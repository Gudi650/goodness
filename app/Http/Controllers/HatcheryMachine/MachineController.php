<?php

namespace App\Http\Controllers\HatcheryMachine;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class MachineController extends Controller
{
    //
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:machines,code'],
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:150'],
            'capacity' => ['required', 'integer', 'min:0'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'manufacturer' => ['nullable', 'string', 'max:150'],
            'installed_date' => ['nullable', 'date'],
            'status' => ['required', 'in:Active,Under Maintenance,Inactive'],
            'iot_enabled' => ['nullable', 'boolean'],
            'technician_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $machine = Machine::create($data);

        return back()->with('success', "Machine {$machine->code} added.");
    }

    public function update(Request $request, Machine $machine): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:machines,code,' . $machine->id],
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:150'],
            'capacity' => ['required', 'integer', 'min:0'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'manufacturer' => ['nullable', 'string', 'max:150'],
            'installed_date' => ['nullable', 'date'],
            'status' => ['required', 'in:Active,Under Maintenance,Inactive'],
            'iot_enabled' => ['nullable', 'boolean'],
            'technician_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $machine->update($data);

        return back()->with('success', "Machine {$machine->code} updated.");
    }

    public function destroy(Machine $machine): RedirectResponse
    {
        $machine->delete();

        return back()->with('success', "Machine {$machine->code} removed.");
    }
}
