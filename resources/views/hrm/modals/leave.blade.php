<div id="addLeaveModal" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-xl mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold font-display">Request Leave</h2>
            <button type="button" onclick="closeAddLeaveModal()" class="text-slate-400 hover:text-slate-600 transition-colors">✕</button>
        </div>

        <form id="leaveRequestForm" class="space-y-4" method="POST" action="{{ route('leaves.store') }}" onsubmit="return submitLeaveRequest(event)">
            @csrf
            <div>
                <label class="block text-sm text-slate-600">Employee</label>
                <div class="mt-1 w-full border border-slate-300 rounded-md px-3 py-2 text-sm bg-slate-50 text-slate-700 font-medium">
                    {{ auth()->user()->name ?? 'Employee' }}
                </div>
            </div>

            <div>
                <label for="leaveType" class="block text-sm text-slate-600">Leave Type</label>
                <select id="leaveType" name="leave_type" required class="mt-1 w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600">
                    <option value="Annual Leave">Annual Leave</option>
                    <option value="Sick Leave">Sick Leave</option>
                    <option value="Maternity Leave">Maternity Leave</option>
                    <option value="Paternity Leave">Paternity Leave</option>
                    <option value="Compassionate Leave">Compassionate Leave</option>
                    <option value="Unpaid Leave">Unpaid Leave</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="leaveFrom" class="block text-sm text-slate-600">From Date</label>
                    <input id="leaveFrom" name="from_date" type="date" required class="mt-1 w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" />
                </div>
                <div>
                    <label for="leaveTo" class="block text-sm text-slate-600">To Date</label>
                    <input id="leaveTo" name="to_date" type="date" required class="mt-1 w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="leaveDays" class="block text-sm text-slate-600">Days</label>
                    <input id="leaveDays" name="days" type="number" min="1" required class="mt-1 w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" />
                </div>
                <div>
                    <label for="leaveReason" class="block text-sm text-slate-600">Reason</label>
                    <input id="leaveReason" name="reason" type="text" placeholder="Optional reason" class="mt-1 w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeAddLeaveModal()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
                <button id="leaveSubmitBtn" type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Submit Request</button>
            </div>
        </form>
    </div>
</div>