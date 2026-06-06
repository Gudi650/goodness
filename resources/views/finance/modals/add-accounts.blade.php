<div id="addAccountModal" class="hidden">
    <!-- Form -->
    <form class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-600">Bank Name</label>
            <select
                class="mt-1 w-full border border-slate-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500">
                <option value="">Select a bank</option>
                <option value="CRDB">CRDB Bank</option>
                <option value="NMB">NMB Bank</option>
                <option value="NBC">NBC Bank</option>
                <option value="Stanbic">Stanbic Bank</option>
                <option value="Exim">Exim Bank</option>
                <option value="KCB">KCB Bank</option>
                <option value="Equity">Equity Bank</option>
                <option value="StandardChartered">Standard Chartered Bank</option>
                <option value="DTB">Diamond Trust Bank (DTB)</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-600">Account Name</label>
            <input type="text"
                class="mt-1 w-full border border-slate-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500"
                placeholder="e.g. Main Operating Account">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-600">Account Number</label>
            <input type="text"
                class="mt-1 w-full border border-slate-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500"
                placeholder="ACC-0009">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-600">Card Number</label>
            <input type="text"
                class="mt-1 w-full border border-slate-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500"
                placeholder="4111 2222 3333 4444">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-600">Company</label>
            <input type="text"
                class="mt-1 w-full border border-slate-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Goodness Group">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600">Currency</label>
                <select
                    class="mt-1 w-full border border-slate-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500">
                    <option>TZS</option>
                    <option>USD</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600">Initial Balance</label>
                <input type="number"
                    class="mt-1 w-full border border-slate-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="0">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-600">Description</label>
            <textarea class="mt-1 w-full border border-slate-300 rounded px-3 py-2 focus:ring-green-500 focus:border-green-500"
                rows="3" placeholder="Short description of the account"></textarea>
        </div>

        <!-- Footer -->
        <div class="flex justify-end space-x-3 mt-6">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 border border-slate-300 rounded hover:bg-slate-100">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save
                Account</button>
        </div>
    </form>

</div>
