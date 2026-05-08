<div id="modalAddPO" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg max-w-lg w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium">Add Purchase Order</h3>
            <button onclick="closeLocalModal('modalAddPO')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <form method="POST" action="#" onsubmit="submitAddPO(event)">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">PO Number</label>
                    <input type="text" name="poNumber" placeholder="PO-001"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Supplier ID</label>
                    <input type="text" name="supplierId" placeholder="SUP-001"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Order Date</label>
                    <input type="date" name="orderDate"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Expected Delivery Date</label>
                    <input type="date" name="expectedDeliveryDate"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        <option>Pending</option>
                        <option>Approved</option>
                        <option>Received</option>
                        <option>Partially Received</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Total Order Value</label>
                    <input type="number" step="0.01" name="amount" placeholder="0.00"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Created By</label>
                    <input type="text" name="createdBy" placeholder="EMP-001"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="closeLocalModal('modalAddPO')"
                    class="px-4 py-2 border rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-md">Save</button>
            </div>
        </form>
    </div>
</div>
