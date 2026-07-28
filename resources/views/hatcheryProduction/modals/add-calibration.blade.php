<div id="modal-add-calibration" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-lg rounded-lg bg-white shadow-lg">
        <form action="{{ route('maintenance.calibration.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center justify-between border-b px-5 py-4">
                <h3 class="text-lg font-semibold">Add Calibration Record</h3>
                <button type="button" onclick="closeMmModal('modal-add-calibration')" class="text-slate-400 hover:text-slate-600">&times;</button>
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
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Component</label>
                    <input type="text" name="component" required maxlength="150" placeholder="e.g. Temperature sensor"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Calibration Date</label>
                        <input type="date" name="calibration_date" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Next Due</label>
                        <input type="date" name="next_due" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Performed By</label>
                    <select name="performed_by_id" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                        <option value="">-- None --</option>
                        @foreach ($technicians ?? [] as $technician)
                            <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Certificate (PDF/JPG/PNG, max 5MB)</label>
                    <input type="file" name="certificate" accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Notes</label>
                    <textarea name="notes" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t px-5 py-4">
                <button type="button" onclick="closeMmModal('modal-add-calibration')"
                    class="rounded border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">Save Record</button>
            </div>
        </form>
    </div>
</div>