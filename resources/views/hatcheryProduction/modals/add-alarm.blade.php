<div id="modal-add-alarm" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-lg rounded-lg bg-white shadow-lg">
        <form action="{{ route('maintenance.alarms.store') }}" method="POST">
            @csrf
            <div class="flex items-center justify-between border-b px-5 py-4">
                <h3 class="text-lg font-semibold">Log Alarm</h3>
                <button type="button" onclick="closeMmModal('modal-add-alarm')" class="text-slate-400 hover:text-slate-600">&times;</button>
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
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Alarm Type</label>
                        <select name="alarm_type" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="Temp High">Temp High</option>
                            <option value="Temp Low">Temp Low</option>
                            <option value="Humidity Out of Range">Humidity Out of Range</option>
                            <option value="Power Failure">Power Failure</option>
                            <option value="Turning Fault">Turning Fault</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Severity</label>
                        <select name="severity" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="Critical">Critical</option>
                            <option value="Warning">Warning</option>
                            <option value="Info">Info</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Triggered At</label>
                    <input type="datetime-local" name="triggered_at" required value="{{ now()->format('Y-m-d\TH:i') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Notes</label>
                    <textarea name="notes" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t px-5 py-4">
                <button type="button" onclick="closeMmModal('modal-add-alarm')"
                    class="rounded border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">Save Alarm</button>
            </div>
        </form>
    </div>
</div>