<div id="modal-add-sensor" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-lg rounded-lg bg-white shadow-lg">
        <form action="{{ route('maintenance.sensors.store') }}" method="POST">
            @csrf
            <div class="flex items-center justify-between border-b px-5 py-4">
                <h3 class="text-lg font-semibold">Register IoT Sensor</h3>
                <button type="button" onclick="closeMmModal('modal-add-sensor')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <div class="space-y-4 px-5 py-4">
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Machine</label>
                    <select name="machine_id" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                        <option value="" disabled selected>Select machine</option>
                        @foreach ($machines ?? [] as $machine)
                            <option value="{{ $machine->id }}">{{ $machine->code }} — {{ $machine->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Sensor Code</label>
                    <input type="text" name="sensor_code" required maxlength="100" placeholder="e.g. SEN-001"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    <p class="mt-1 text-xs text-slate-400">This code is what the physical sensor uses when it posts readings to the ingestion endpoint.</p>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Type</label>
                    <select name="type" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                        <option value="Temperature">Temperature</option>
                        <option value="Humidity">Humidity</option>
                        <option value="CO2">CO2</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t px-5 py-4">
                <button type="button" onclick="closeMmModal('modal-add-sensor')"
                    class="rounded border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">Save Sensor</button>
            </div>
        </form>
    </div>
</div>