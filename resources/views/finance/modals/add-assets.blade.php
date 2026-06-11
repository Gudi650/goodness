<!-- Add Asset Modal -->
<div id="addAssetsModal" class="hidden">

    <!-- Form -->
    <form action="{{ route('assets.store') }}" method="POST" id="assetForm" class="space-y-4" onsubmit="showAssetCreateLoader()">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700">Asset Name</label>
            <input type="text" name="name"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Company</label>
            <select name="company_id"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                <option value="">Select Company</option>
                @if (isset($companies))
                    @foreach ($companies as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Type</label>
            <select name="type"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                <option value="">Select Type</option>
                <option>Fixed Asset</option>
                <option>Current Asset</option>
                <option>Intangible Asset</option>
                <option>Investment</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Term</label>
            <select name="term"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                <option value="">Select Term</option>
                <option>Short-term</option>
                <option>Long-term</option>
            </select>
        </div>

        <!-- Category & Sub-category -->
        <div>
            <label class="block text-sm font-medium text-slate-700">Category</label>
            <select name="category_id" id="assetCategorySelect"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-green-500">
                <!-- Options populated from categories table -->
                <option value="">Select Category</option>
                @if (isset($assetsCategories))
                    @foreach ($assetsCategories as $category)
                        <option value="{{ $category['id'] }}">{{ $category['category'] }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Original Value</label>
                <input type="number" name="original_value"
                    class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Current Value</label>
                <input type="number" name="current_value"
                    class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        {{-- depriciation value --}}
        <div>
            <label class="block text-sm font-medium text-slate-700">Depreciation Value(%) per Year</label>
            <input type="number" name="depreciation_value"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
        </div>


        <div>
            <label class="block text-sm font-medium text-slate-700">Acquisition Date</label>
            <input type="date" name="acquired"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Status</label>
            <select name="status"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                <option value="">Select Status</option>
                <option>Active</option>
                <option>Disposed</option>
                <option>Sold</option>
                <option>Written Off</option>
            </select>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-3 pt-4">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 border border-slate-300 rounded hover:bg-slate-100">Cancel</button>

            <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">Save
                Asset</button>

        </div>
    </form>

</div>

<x-loading id="AssetCreateLoader" fullPage="true" class="hidden" />

<script>
    // Show loading indicator on form submit
    document.querySelector('#addAssetModal form').addEventListener('submit', function() {
        document.getElementById('AssetCreateLoader').classList.remove('hidden');
    });

    function showAssetCreateLoader() {
        const loader = document.getElementById('AssetCreateLoader');
        if (loader) loader.classList.remove('hidden');
    }
</script>
