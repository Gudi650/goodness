<div id="addDepartmentModal" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-lg mx-4 p-6">
        <h2 class="text-lg font-semibold font-display mb-4" id="departmentModalTitle">Add Department</h2>
        <form id="departmentForm" method="POST" action="{{ route('departments.store') }}" class="space-y-6" onsubmit="return showDepartmentLoader(event)">
            @csrf
            <input type="hidden" id="departmentEditingId" value="">
            <div class="space-y-4">
                @if ($isAdmin)
                    <select name="company_id" id="deptCompany" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" required>
                        <option value="">Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" @selected((string) $activeCompanyId === (string) $company->id)>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" name="company_id" value="{{ auth()->user()?->company_id }}">
                @endif
                <input type="text" name="name" placeholder="Department Name" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="deptName" required />
                <textarea name="description" placeholder="Description (optional)" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" id="deptDesc" rows="3"></textarea>
            </div>

            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeAddDepartmentModal()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
                <button type="submit" id="departmentSubmitBtn" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Save</button>
            </div>
        </form>
        <x-loading id="departmentLoader" size="lg" color="amber" full-page="true" message="Saving department details..." :show="false" />
    </div>
</div>

<x-loading id="departmentDeleteLoader" size="lg" color="amber" full-page="true" message="Deleting department..." :show="false" />
