<!-- Item Creation -->
<div id="addItemsModal" class="bg-white border rounded shadow-sm p-6 mb-8 hidden ">
    <h2 class="text-lg font-semibold mb-4">Create Item</h2>

    <form action="{{ route('items.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6"
        onsubmit="showItemsCreateLoader()">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700">Item Name</label>
            <input name="item_name" type="text" class="mt-1 block w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Category</label>
            <select name="category_id" class="mt-1 block w-full border rounded px-3 py-2">
                <option>Choose Category</option>

                @forelse ($itemsCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @empty
                    <option value="">No categories available</option>
                @endforelse

            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700">Notes</label>
            <textarea name="description" rows="2" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="md:col-span-2 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add
                Item</button>
        </div>

    </form>

</div>

<x-loading id="itemsCreateLoader" fullPage="true" class="hidden" />

<script>
    function showItemsCreateLoader() {
        const loader = document.getElementById('itemsCreateLoader');
        if (loader) {
            loader.classList.remove('hidden');
        }
    }
</script>
