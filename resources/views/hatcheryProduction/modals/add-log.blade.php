<div id="modal-add-log" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-lg rounded-lg bg-white shadow-lg">
        <form action="{{ route('maintenance.logs.store') }}" method="POST">
            @csrf
            <div class="flex items-center justify-between border-b px-5 py-4">
                <h3 class="text-lg font-semibold">Log Machine Reading</h3>
                <button type="button" onclick="closeMmModal('modal-add-log')" class="text-slate-400 hover:text-slate-600">&times;</button>
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

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Date</label>
                        <input type="date" name="log_date" required value="{{ now()->toDateString() }}"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Shift</label>
                        <select name="shift" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="Morning">Morning</option>
                            <option value="Afternoon">Afternoon</option>
                            <option value="Night">Night</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Temp (°C)</label>
                        <input type="number" step="0.1" name="temperature" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Humidity (%)</label>
                        <input type="number" step="0.1" name="humidity" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Turning Count</label>
                        <input type="number" min="0" name="turning_count" required value="0"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Notes</label>
                    <textarea name="notes" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm"></textarea>
                </div>

                <p class="text-xs text-slate-400">Normal/Flagged status is calculated automatically from temperature and humidity — no need to set it here.</p>
            </div>

            <div class="flex justify-end gap-3 border-t px-5 py-4">
                <button type="button" onclick="closeMmModal('modal-add-log')"
                    class="rounded border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">Save Log</button>
            </div>
        </form>
    </div>
</div>