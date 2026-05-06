<div id="addPaymentModal" class="space-y-4 hidden">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Payment ID</label>
        <input type="text" id="paymentId" placeholder="PAY-001" class="w-full px-3 py-2 border border-slate-300 rounded-md">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Company</label>
        <input type="text" id="paymentCompany" placeholder="Company name" class="w-full px-3 py-2 border border-slate-300 rounded-md">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Amount (TZS)</label>
        <input type="number" id="paymentAmount" placeholder="0" class="w-full px-3 py-2 border border-slate-300 rounded-md">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Payment Method</label>
        <select id="paymentMethod" class="w-full px-3 py-2 border border-slate-300 rounded-md">
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Cash">Cash</option>
            <option value="Check">Check</option>
        </select>
    </div>
</div>
