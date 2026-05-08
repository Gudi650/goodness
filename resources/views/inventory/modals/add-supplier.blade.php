<div id="modalAddSupplier" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg max-w-lg w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium">Add Supplier</h3>
            <button onclick="closeLocalModal('modalAddSupplier')"
                class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <form method="POST" action="#" onsubmit="submitAddSupplier(event)">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Supplier Name</label>
                    <input type="text" name="name" placeholder="Supplier Inc"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Contact Person</label>
                    <input type="text" name="contact" placeholder="John Doe"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" placeholder="contact@supplier.com"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="closeLocalModal('modalAddSupplier')"
                    class="px-4 py-2 border rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-md">Save</button>
            </div>
        </form>
    </div>
</div>
