<div id="modal-add-schedule" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-lg rounded-lg bg-white shadow-lg">
        <form action="{{ route('maintenance.schedule.store') }}" method="POST">
            @csrf
            <div class="flex items-center justify-between border-b px-5 py-4">
                <h3 class="text-lg font-semibold">Schedule Maintenance</h3>
                <button type="button" onclick="closeMmModal('modal-add-schedule')" class="text-slate-400 hover:text-slate-600">&times;</button>
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
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Maintenance Type</label>
                    <input type="text" name="maintenance_type" required maxlength="150" placeholder="e.g. Fan service, Belt inspection"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Scheduled Date</label>
                        <input type="date" name="scheduled_date" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Frequency</label>
                        <select name="frequency" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="One-off">One-off</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Quarterly">Quarterly</option>
                            <option value="Annual">Annual</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Assigned Technician</label>
                    <select name="technician_id" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                        <option value="">-- None --</option>
                        @foreach ($technicians ?? [] as $technician)
                            <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Notes</label>
                    <textarea name="notes" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t px-5 py-4">
                <button type="button" onclick="closeMmModal('modal-add-schedule')"
                    class="rounded border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">Save Schedule</button>
            </div>
        </form>
    </div>
</div>