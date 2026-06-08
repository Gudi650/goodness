<!-- Add Liability Modal -->
<div id="addLiabilitiesModal" class="hidden">

    <!-- Form -->
    <form id="liabilityForm" class="space-y-4">

        {{-- 
        <div>
            <label class="block text-sm font-medium text-slate-700">Liability Code</label>
            <input type="text" name="code"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                required>
        </div>
        --}}

        <div>
            <label class="block text-sm font-medium text-slate-700">Liability Name</label>
            <input type="text" name="name"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Company</label>
            <select name="company"
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
            <select name="category"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                <option value="">Select Type</option>
                <option>Long-term Loan</option>
                <option>Current Liability</option>
                <option>Long-term Liability</option>
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
            <select name="category_id" id="liabilityCategorySelect"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-green-500">
                <!-- Options populated from categories table -->
                <option value="">Select Category</option>
                @if (isset($liabilityCategories))
                    @foreach ($liabilityCategories as $category)
                        <option value="{{ $category['id'] }}" >{{ $category['category'] }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Creditor</label>
            <input type="text" name="creditor"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Original Amount</label>
                <input type="number" name="original_amount"
                    class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Outstanding Amount</label>
                <input type="number" name="outstanding_amount"
                    class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Interest Rate</label>
            <input type="text" name="interest_rate" placeholder="e.g. 12% or N/A"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Due Date</label>
            <input type="date" name="due_date"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Status</label>
            <select name="status"
                class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                <option value="">Select Status</option>
                <option>Active</option>
                <option>Closed</option>
                <option>Overdue</option>
            </select>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-3 pt-4">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 border border-slate-300 rounded hover:bg-slate-100">Cancel</button>
            <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">Save
                Liability</button>
        </div>
    </form>

</div>

<x-loading id="LiabilityCreateLoader" fullPage="true" class="hidden" />

<script>
    // Show loading indicator on form submit
    document.querySelector('#addLiabilityModal form').addEventListener('submit', function() {
        document.getElementById('LiabilityCreateLoader').classList.remove('hidden');
    });

    function showLiabilityCreateLoader() {
        const loader = document.getElementById('LiabilityCreateLoader');
        if (loader) loader.classList.remove('hidden');
    }
</script>
