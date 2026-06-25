<!-- Add Equity Modal -->
<div id="addEquityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-slate-800">Add Equity</h2>
            <button onclick="closeEquityModal()" class="text-slate-500 hover:text-slate-700">
                ✕
            </button>
        </div>

        <!-- Form -->
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Shareholder Name</label>
                <input type="text" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200" placeholder="Enter shareholder name">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Company</label>
                <input type="text" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200" placeholder="Enter company name">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Equity Type</label>
                <select class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200">
                    <option>Common Stock</option>
                    <option>Preferred Stock</option>
                    <option>Capital Contribution</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Shares</label>
                    <input type="number" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200" placeholder="e.g. 10000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Ownership %</label>
                    <input type="number" step="0.01" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200" placeholder="e.g. 25">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Notes</label>
                <textarea class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200" rows="3" placeholder="Additional notes..."></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeEquityModal()" class="px-4 py-2 rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-md bg-brand-600 text-white hover:bg-brand-700">Save Equity</button>
            </div>

        </form>
    </div>
</div>

<script>
function openEquityModal() {
    document.getElementById('addEquityModal').classList.remove('hidden');
    document.getElementById('addEquityModal').classList.add('flex');
}
function closeEquityModal() {
    document.getElementById('addEquityModal').classList.add('hidden');
    document.getElementById('addEquityModal').classList.remove('flex');
}
</script>
