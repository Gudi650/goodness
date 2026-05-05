<x-loading id="employeeDeleteLoader" size="lg" color="amber" full-page="true" message="Deleting employee..." :show="false" />

<div id="addEmployeeModal" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
        <h2 class="text-lg font-semibold font-display mb-4">Add Employee</h2>
        <form id="employeeForm" method="POST" action="{{ route('employees.store') }}" class="space-y-6" onsubmit="return showEmployeeLoader(event)">
            @csrf
            <div class="space-y-4">
                <input type="text" name="name" placeholder="Full Name" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empName" required />

                <input type="email" name="email" placeholder="Email Address" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empEmail" required />

                <input type="tel" name="phone_number" placeholder="Phone Number " class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empPhone" />

                @if ($isAdmin)
                    <select name="company_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empCompany" required>
                        <option value="">Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" @selected((string) $activeCompanyId === (string) $company->id)>{{ $company->name }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" name="company_id" value="{{ auth()->user()?->company_id }}">
                    <div class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm bg-slate-50 text-slate-600">
                        {{ auth()->user()?->company?->name ?? 'No company assigned' }}
                    </div>
                @endif

                <select name="department_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empDept">
                    <option value="">Select Department</option>
                </select>
                <input type="date" name="join_date" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="empJoinDate" />
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeAddEmployeeModal()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
                <button type="submit" id="employeeSubmitBtn" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Save</button>
            </div>
        </form>

        <x-loading id="employeeLoader" size="lg" color="amber" full-page="true" message="Saving employee details..." :show="false" />
    </div>
</div>
