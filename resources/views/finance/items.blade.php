<div id="itemsPane" class="hidden">



    <!-- Category Filter -->
    <div class="bg-white border rounded shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Filter Items by Category</h2>
        <form class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700">Select Category</label>
                <select class="mt-1 block w-full border rounded px-3 py-2">
                    <option>All Categories</option>

                    @forelse ($itemsCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @empty
                        <option value="">No categories available</option>
                    @endforelse
                    
                </select>
            </div>
            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Apply
                Filter</button>
        </form>
    </div>

    <!-- Items Table -->
    <div class="bg-white border rounded shadow-sm">
        <table class="w-full text-sm">
    <thead class="bg-slate-50 border-b border-slate-200">
        <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Item Name</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Category</th>
            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Notes</th>
            <th class="px-4 py-3 text-center text-xs font-semibold uppercase">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
        @forelse($items as $item)
            <tr class="hover:bg-slate-50">
                <!-- Item Name -->
                <td class="px-4 py-3 font-medium">{{ $item->item_name }}</td>

                <!-- Category (via relationship) -->
                <td class="px-4 py-3">
                    {{ $item->category ? $item->category->category_name : 'No Category' }}
                </td>

                <!-- Notes / Description -->
                <td class="px-4 py-3 text-slate-600">
                    {{ $item->description ?? '-' }}
                </td>

                <!-- Actions -->
                <td class="px-4 py-3 text-center">
                    <div class="flex justify-center gap-2">
                        <a {{-- href="{{ route('items.edit', $item->id) }}"  --}}
                           class="px-3 py-1 bg-yellow-500 text-white rounded text-xs">Edit</a>

                        <form {{-- action="route('items.destroy',$item->id) --}} method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-1 bg-red-600 text-white rounded text-xs">Delete</button>
                        </form>

                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-4 py-3 text-center text-slate-500">No items found</td>
            </tr>
        @endforelse
    </tbody>
</table>

    </div>
</div>
