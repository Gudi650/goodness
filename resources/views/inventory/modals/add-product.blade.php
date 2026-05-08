<div id="modalAddProduct" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg max-w-lg w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium">Add Product</h3>
            <button onclick="closeLocalModal('modalAddProduct')"
                class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <form method="POST" action="#" onsubmit="submitAddProduct(event)">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Inventory ID</label>
                    <input type="text" name="inventory_id" placeholder="INV-1004"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Product Name</label>
                    <input type="text" name="name" placeholder="Widget C"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                    <input type="text" name="category" placeholder="Gadgets"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Unit of Measure</label>
                    <input type="text" name="unit_of_measure" placeholder="pieces"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Quantity in Stock</label>
                    <input type="number" name="stock" placeholder="0"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Cost per Unit</label>
                    <input type="number" step="0.01" name="cost_per_unit" placeholder="0.00"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Location</label>
                    <input type="text" name="location" placeholder="Main Warehouse"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Reorder Level</label>
                    <input type="number" name="reorder_level" placeholder="40"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Supplier ID</label>
                    <input type="text" name="supplier_id" placeholder="SUP-001"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="closeLocalModal('modalAddProduct')"
                    class="px-4 py-2 border rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-md">Save</button>
            </div>
        </form>
    </div>
</div>
