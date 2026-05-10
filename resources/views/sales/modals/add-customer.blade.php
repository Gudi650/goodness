<div id="modalAddCustomer" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 overflow-y-auto">
    <div class="bg-white rounded-lg max-w-2xl w-full m-4 my-8">
        <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between rounded-t-lg">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Add Customer</h3>
                <p class="text-xs text-slate-500 mt-1">Create a new customer record</p>
            </div>
            <button onclick="closeLocalModal('modalAddCustomer'); resetCustomerForm();" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form id="customerForm" onsubmit="submitAddCustomer(event)">
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm text-slate-600">Name<input id="customer_name" class="mt-1 block w-full border border-slate-200 rounded p-2" required /></label>
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Company<input id="customer_company" class="mt-1 block w-full border border-slate-200 rounded p-2" /></label>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <label class="block text-sm text-slate-600">Phone<input id="customer_phone" class="mt-1 block w-full border border-slate-200 rounded p-2" /></label>
                    <label class="block text-sm text-slate-600">Email<input id="customer_email" class="mt-1 block w-full border border-slate-200 rounded p-2" type="email" /></label>
                </div>
            </div>

            <div class="px-6 py-4 flex gap-3 justify-end border-t border-slate-100">
                <button type="button" onclick="closeLocalModal('modalAddCustomer'); resetCustomerForm();" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium">Cancel</button>
                <button type="submit" id="submitCustomerBtn" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add Customer</button>
            </div>
        </form>
    </div>
</div>
