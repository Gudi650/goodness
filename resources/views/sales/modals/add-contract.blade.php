<div id="modalAddContract" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 overflow-y-auto">
    <div class="bg-white rounded-lg max-w-2xl w-full m-4 my-8">
        <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between rounded-t-lg">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Add Contract</h3>
                <p class="text-xs text-slate-500 mt-1">Create a new contract</p>
            </div>
            <button onclick="closeLocalModal('modalAddContract'); resetContractForm();" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form id="contractForm" onsubmit="submitAddContract(event)">
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm text-slate-600">Contract No.<input id="contract_no" class="mt-1 block w-full border border-slate-200 rounded p-2" required /></label>
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Client<input id="contract_client" class="mt-1 block w-full border border-slate-200 rounded p-2" /></label>
                </div>
                <div>
                    <label class="block text-sm text-slate-600">Value<input id="contract_value" type="number" class="mt-1 block w-full border border-slate-200 rounded p-2" /></label>
                </div>
            </div>

            <div class="px-6 py-4 flex gap-3 justify-end border-t border-slate-100">
                <button type="button" onclick="closeLocalModal('modalAddContract'); resetContractForm();" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium">Cancel</button>
                <button type="submit" id="submitContractBtn" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add Contract</button>
            </div>
        </form>
    </div>
</div>
