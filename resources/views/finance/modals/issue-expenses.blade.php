<!-- Liability Category Modal -->
<div id="issueExpensesModal" class="hidden ">

    <form action="" method="POST" id="issueExpensesForm" class="space-y-4" onsubmit="showissueExpensesCreateLoader()">
        @csrf

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Bank </label>
            <select id="expenseBank" name="bank_id" required
                class="w-full rounded-md border border-slate-200 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-brand-500">
                <option value="">Select bank...</option>

                @foreach ($virtualAccounts as $account)
                    <option value="{{ $account['id'] }}" @selected(isset($currentBankId) && (string) $currentBankId === (string) $account['id'])>
                        {{ $account['account_name'] }}
                    </option>
                @endforeach

            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" class="mt-1 w-full border rounded-lg px-3 py-2 text-sm" rows="3"
                placeholder="Enter description"></textarea>
        </div>


        <div class="flex justify-end gap-3 pt-4">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 border border-slate-300 rounded hover:bg-slate-100">Cancel</button>
            <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg">Save</button>
        </div>
    </form>

</div>

<x-loading id="liabilityCategoryCreateLoader" fullPage="true" class="hidden" />

<script>
    function showissueExpensesCreateLoader() {
        const loader = document.getElementById('issueExpensesCreateLoader');
        if (loader) {
            loader.classList.remove('hidden');
        }
    }
</script>
