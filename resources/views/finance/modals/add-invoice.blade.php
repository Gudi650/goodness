<div id="addInvoiceModal" class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Invoice ID</label>
        <input type="text" id="invoiceId" placeholder="INV-003" class="w-full px-3 py-2 border border-slate-300 rounded-md">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Company</label>
        <input type="text" id="invoiceCompany" placeholder="Company name" class="w-full px-3 py-2 border border-slate-300 rounded-md">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Amount (TZS)</label>
        <input type="number" id="invoiceAmount" placeholder="0" class="w-full px-3 py-2 border border-slate-300 rounded-md">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
        <select id="invoiceStatus" class="w-full px-3 py-2 border border-slate-300 rounded-md">
            <option value="Paid">Paid</option>
            <option value="Unpaid">Unpaid</option>
        </select>
    </div>
</div>
