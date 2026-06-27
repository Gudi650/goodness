<!-- Add Equity Modal -->
<div id="addEquityModal" class= "bg-white border rounded shadow-sm p-6 mb-8 hidden">
    <!-- Form -->
    <form class="space-y-4">
        <!-- Company -->
        <div>
            <label class="block text-sm font-medium text-slate-700">Company</label>
            <select name="company"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200">
                <option value="">Select company...</option>
                <option value="alpha">Alpha Corp</option>
                <option value="beta">Beta Ltd</option>
                <option value="gamma">Gamma Investments</option>
            </select>
        </div>

        <!-- Shareholder -->
        <div>
            <label class="block text-sm font-medium text-slate-700">Shareholder</label>
            <select name="shareholder"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200">
                <option value="">Select shareholder...</option>
                <option value="john">John Doe</option>
                <option value="jane">Jane Smith</option>
                <option value="gamma">Gamma Investments Ltd</option>
            </select>
        </div>

        <!-- Equity Type -->
        <div>
            <label class="block text-sm font-medium text-slate-700">Equity Type</label>
            <select name="equity_type"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200">
                <option>Common Stock</option>
                <option>Preferred Stock</option>
                <option>Capital Contribution</option>
            </select>
        </div>

        <!-- Shares & Ownership -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Shares Issued</label>
                <input type="number" name="shares"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200"
                    placeholder="e.g. 10000">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Ownership %</label>
                <input type="number" step="0.01" name="ownership"
                    class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200"
                    placeholder="e.g. 25">
            </div>
        </div>

        <!-- Value -->
        <div>
            <label class="block text-sm font-medium text-slate-700">Value Held (TZS)</label>
            <input type="number" name="value"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-brand-400 focus:ring focus:ring-brand-200"
                placeholder="e.g. 2500000">
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
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">Cancel</button>

            <button type="submit" class="px-4 py-2 rounded-md bg-brand-600 text-white hover:bg-brand-700">Save
                Equity</button>

        </div>

    </form>

</div>
