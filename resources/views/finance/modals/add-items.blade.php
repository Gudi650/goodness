<!-- Item Creation -->
<div id="addItemsModal" class="bg-white border rounded shadow-sm p-6 mb-8 hidden ">
    <h2 class="text-lg font-semibold mb-4">Create Item</h2>
    <form class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label class="block text-sm font-medium text-slate-700">Item Name</label>
            <input type="text" class="mt-1 block w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Category</label>
            <select class="mt-1 block w-full border rounded px-3 py-2">
                <option>Office Supplies</option>
                <option>Travel</option>
                <option>Utilities</option>
            </select>
        </div>
        
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700">Notes</label>
            <textarea rows="2" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="md:col-span-2 flex justify-end">
            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add
                Item</button>
        </div>
        
    </form>
</div>
