<div id="modal-add-machine" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-lg rounded-lg bg-white shadow-lg">
        <form action="{{ route('maintenance.machines.store') }}" method="POST">
            @csrf
            <div class="flex items-center justify-between border-b px-5 py-4">
                <h3 class="text-lg font-semibold">Add Machine</h3>
                <button type="button" onclick="closeMmModal('modal-add-machine')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <div class="max-h-[70vh] space-y-4 overflow-y-auto px-5 py-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Code</label>
                        <input type="text" name="code" required maxlength="50"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Name</label>
                        <input type="text" name="name" required maxlength="150"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Type</label>
                        <select name="type" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="Setter">Setter</option>
                            <option value="Hatcher">Hatcher</option>
                            <option value="Combo">Combo (Setter/Hatcher)</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Location</label>
                        <input type="text" name="location" maxlength="150"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Capacity (eggs)</label>
                        <input type="number" name="capacity" min="0" required
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Installed Date</label>
                        <input type="date" name="installed_date"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Serial Number</label>
                        <input type="text" name="serial_number" maxlength="100"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Manufacturer</label>
                        <input type="text" name="manufacturer" maxlength="150"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Status</label>
                        <select name="status" required class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                            <option value="Active">Active</option>
                            <option value="Under Maintenance">Under Maintenance</option>
                            <option value="Inactive">Inactive</option>
                        </select>
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
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="iot_enabled" value="1" class="rounded border-slate-300">
                    IoT sensors installed on this machine
                </label>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Notes</label>
                    <textarea name="notes" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t px-5 py-4">
                <button type="button" onclick="closeMmModal('modal-add-machine')"
                    class="rounded border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded bg-brand-600 px-4 py-2 text-sm text-white hover:bg-brand-700">Save Machine</button>
            </div>
        </form>
    </div>
</div>