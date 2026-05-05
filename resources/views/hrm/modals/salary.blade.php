<div id="recordSalaryModal" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
        <h2 class="text-lg font-semibold font-display mb-4" id="modalTitle">Record Salary</h2>
        <form id="salaryForm" method="POST" action="{{ route('payroll.store') }}" class="space-y-4" onsubmit="return showSalaryLoader(event)">
            <input type="hidden" id="editingId" name="editing_id" value="">
            @csrf
            <div class="space-y-3">
                <label class="block text-sm text-slate-600">Employee
                    @php $recorded = collect($salaries ?? [])->pluck('user_id')->all(); @endphp
                    <select name="user_id" required class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm">
                        <option value="">Select employee</option>
                        @foreach ($employees as $emp)
                            @if(!in_array($emp['id'], $recorded))
                                <option value="{{ $emp['id'] }}">{{ $emp['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </label>

                <label class="block text-sm text-slate-600">Basic Salary
                    <input id="basicSalary" name="amount" type="number" step="0.01" min="0" required class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm" />
                </label>

                <label class="block text-sm text-slate-600">Deductions
                    <input id="deductions" name="deductions" type="number" step="0.01" min="0" class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm" value="0" />
                </label>

                <label class="block text-sm text-slate-600">Net Salary
                    <input id="netSalary" name="net_amount" type="number" step="0.01" min="0" required class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm bg-slate-50" readonly />
                </label>

                <div class="grid grid-cols-2 gap-3">
                    <label class="block text-sm text-slate-600">Currency
                        <input name="currency" type="text" class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm" value="USD" />
                    </label>
                    <label class="block text-sm text-slate-600">Effective Date
                        <input name="effective_date" type="date" class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm" />
                    </label>
                </div>

                <label class="block text-sm text-slate-600">Notes (optional)
                    <textarea name="notes" rows="3" class="mt-1 block w-full border border-slate-300 rounded p-2 text-sm"></textarea>
                </label>
            </div>

            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeRecordSalaryModal()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
                <button type="submit" id="salarySubmitBtn" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Save</button>
            </div>
        </form>
        <x-loading id="salaryLoader" size="lg" color="amber" full-page="true" message="Saving salary..." :show="false" />
    </div>
</div>
