<!-- Issue Dividend Modal -->
<div id="issueDividendModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white border rounded-xl shadow-lg w-full max-w-lg p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-slate-800">Issue Dividend</h2>
            <button onclick="closeDividendModal()" class="text-slate-500 hover:text-slate-700">✕</button>
        </div>

        <!-- Form -->
        <form action={{ route('dividends.store') }} method="POST" class="space-y-4" onsubmit="showDividendCreateLoader()">
            @csrf

            <!-- Company -->
            <div>
                <label class="block text-sm font-medium text-slate-700">Company</label>
                <select name="company_id"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200">
                    <option value="">Select company...</option>

                    @forelse ($sharesDefinitions as $share)
                        <option value="{{ $share->company->id }}">{{ $share->company->name }}</option>
                    @empty
                        <option class="text-sm text-red-500" disabled>Please add equity definitions of the companies first.</option>
                    @endforelse

                </select>
            </div>

            <!-- Dividend Amount -->
            <div>
                <label class="block text-sm font-medium text-slate-700">Total Dividend Amount (TZS)</label>
                <input type="number" name="amount"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200"
                    placeholder="e.g. 20000000">
            </div>

            <!-- Declared Date -->
            <div>
                <label class="block text-sm font-medium text-slate-700">Declared Date</label>
                <input type="date" name="declared_at"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200">
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-slate-700">Notes</label>
                <textarea name="notes"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200"
                    rows="3" placeholder="Additional notes..."></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeDividendModal()"
                    class="px-4 py-2 rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 rounded-md bg-brand-600 text-white hover:bg-brand-700">Distribute Dividend</button>
            </div>
        </form>
    </div>
</div>

{{-- adding the loader when the user submits the datas as well here --}}
<x-loading id="IssueDividendCreateLoader" fullPage="true" class="hidden" />

<script>
    function openDividendModal() {
        document.getElementById('issueDividendModal').classList.remove('hidden');
        document.getElementById('issueDividendModal').classList.add('flex');
    }

    function closeDividendModal() {
        document.getElementById('issueDividendModal').classList.add('hidden');
        document.getElementById('issueDividendModal').classList.remove('flex');
    }

    // Show loading indicator on form submit
    document.querySelector('#issueDividendModal form').addEventListener('submit', function() {
        document.getElementById('IssueDividendCreateLoader').classList.remove('hidden');
    });

    function showDividendCreateLoader() {
        const loader = document.getElementById('IssueDividendCreateLoader');
        if (loader) loader.classList.remove('hidden');
    }
</script>
