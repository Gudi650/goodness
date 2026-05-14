<script>
    const employeeNames = @json($employeeNames);
    const departments = @json($departments);
    const departmentOptions = @json($departmentOptions);
    let previewedData = null;

    function getDragDropZone() {
        return document.getElementById('dragDropZone');
    }

    function setImportStatus(message, type = 'info') {
        const status = document.getElementById('importStatus');
        if (!status) return;

        const styles = {
            info: 'border-blue-200 bg-blue-50 text-blue-700',
            success: 'border-emerald-200 bg-emerald-50 text-emerald-700',
            error: 'border-red-200 bg-red-50 text-red-700',
        };

        status.className = `rounded-md border px-3 py-2 text-sm ${styles[type] || styles.info}`;
        status.textContent = message;
        status.classList.remove('hidden');
    }

    function clearImportStatus() {
        const status = document.getElementById('importStatus');
        if (!status) return;
        status.textContent = '';
        status.classList.add('hidden');
    }

    function setImportLoading(isLoading, message = '') {
        const loader = document.getElementById('importLoader');
        const loaderText = document.getElementById('importLoaderText');
        const previewBtn = document.getElementById('previewBtn');
        const confirmBtn = document.getElementById('confirmBtn');

        if (loader) {
            loader.classList.toggle('hidden', !isLoading);
            loader.classList.toggle('flex', isLoading);
        }

        if (loaderText && message) {
            loaderText.textContent = message;
        }

        if (previewBtn) {
            previewBtn.disabled = isLoading;
        }

        if (confirmBtn) {
            confirmBtn.disabled = isLoading;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const dragDropZone = getDragDropZone();

        if (!dragDropZone) return;

        dragDropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dragDropZone.classList.add('bg-brand-50', 'border-brand-400');
        });

        dragDropZone.addEventListener('dragleave', () => {
            dragDropZone.classList.remove('bg-brand-50', 'border-brand-400');
        });

        dragDropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dragDropZone.classList.remove('bg-brand-50', 'border-brand-400');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('csvFileInput').files = files;
                handleFileSelect({ target: { files } });
            }
        });

        dragDropZone.addEventListener('click', () => {
            document.getElementById('csvFileInput').click();
        });
    });

    function handleFileSelect(event) {
        const files = event.target.files;
        if (files.length > 0) {
            document.getElementById('previewBtn').disabled = false;
            clearImportStatus();

            const selectedCsvFile = document.getElementById('selectedCsvFile');
            const dragDropPrompt = document.getElementById('dragDropPrompt');

            if (selectedCsvFile) {
                selectedCsvFile.textContent = `✓ ${files[0].name}`;
                selectedCsvFile.classList.remove('hidden');
            }

            if (dragDropPrompt) {
                dragDropPrompt.classList.add('hidden');
            }
        }
    }

    function openBulkImportModal() {
        document.getElementById('bulkImportModal').classList.remove('hidden');
        const csvFileInput = document.getElementById('csvFileInput');
        if (csvFileInput) {
            csvFileInput.value = '';
        }
        document.getElementById('previewBtn').disabled = true;
        setImportLoading(false);
        clearImportStatus();

        const selectedCsvFile = document.getElementById('selectedCsvFile');
        const dragDropPrompt = document.getElementById('dragDropPrompt');

        if (selectedCsvFile) {
            selectedCsvFile.textContent = '';
            selectedCsvFile.classList.add('hidden');
        }

        if (dragDropPrompt) {
            dragDropPrompt.classList.remove('hidden');
        }
        previewedData = null;
    }

    function closeBulkImportModal() {
        const modal = document.getElementById('bulkImportModal');
        const csvFileInput = document.getElementById('csvFileInput');
        const selectedCsvFile = document.getElementById('selectedCsvFile');
        const dragDropPrompt = document.getElementById('dragDropPrompt');
        const previewBtn = document.getElementById('previewBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const importStep1 = document.getElementById('importStep1');
        const importStep2 = document.getElementById('importStep2');
        const importStep3 = document.getElementById('importStep3');

        if (modal) {
            modal.classList.add('hidden');
        }

        if (csvFileInput) {
            csvFileInput.value = '';
        }

        if (selectedCsvFile) {
            selectedCsvFile.textContent = '';
            selectedCsvFile.classList.add('hidden');
        }

        if (dragDropPrompt) {
            dragDropPrompt.classList.remove('hidden');
        }

        if (previewBtn) {
            previewBtn.disabled = true;
        }

        if (confirmBtn) {
            confirmBtn.disabled = true;
        }

        if (importStep1) {
            importStep1.classList.remove('hidden');
        }

        if (importStep2) {
            importStep2.classList.add('hidden');
        }

        if (importStep3) {
            importStep3.classList.add('hidden');
        }

        setImportLoading(false);
        clearImportStatus();
        previewedData = null;
    }

    function previewImport() {
        const fileInput = document.getElementById('csvFileInput');
        if (!fileInput) {
            setImportStatus('CSV file input was not found. Reopen the import modal and try again.', 'error');
            if (window.showAlert) window.showAlert('error', 'CSV file input was not found.');
            return;
        }

        const file = fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
        const companyId = document.getElementById('bulkImportCompany').value;

        if (!file) {
            setImportStatus('Please select a CSV file first.', 'error');
            if (window.showAlert) window.showAlert('error', 'Please select a CSV file');
            return;
        }

        if (!companyId) {
            setImportStatus('Please select a company first.', 'error');
            if (window.showAlert) window.showAlert('error', 'Please select a company');
            return;
        }

        const formData = new FormData();
        formData.append('csv_file', file);
        formData.append('company_id', companyId);

        setImportLoading(true, 'Validating CSV...');
        setImportStatus('Validating file and checking rows...', 'info');

        fetch('{{ route("bulk-import.preview") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData,
        })
        .then(async response => {
            const text = await response.text();
            let payload = {};

            try {
                payload = text ? JSON.parse(text) : {};
            } catch (error) {
                payload = { message: text || 'Invalid server response.' };
            }

            if (!response.ok) {
                throw new Error(payload.message || 'Preview failed');
            }

            return payload;
        })
        .then(data => {
            setImportLoading(false);

            if (data.success) {
                previewedData = data.valid_rows;
                displayPreview(data);
                document.getElementById('importStep1').classList.add('hidden');
                document.getElementById('importStep2').classList.remove('hidden');
                setImportStatus(`Preview completed: ${data.valid_count} valid, ${data.error_count} errors.`, 'success');
                if (data.valid_count > 0) {
                    document.getElementById('confirmBtn').disabled = false;
                }
            } else {
                setImportStatus(data.message || 'Preview failed', 'error');
                if (window.showAlert) window.showAlert('error', data.message || 'Preview failed');
            }
        })
        .catch(error => {
            setImportLoading(false);
            setImportStatus(error.message || 'Preview request failed.', 'error');
            if (window.showAlert) window.showAlert('error', 'Error: ' + error.message);
        });
    }

    function displayPreview(data) {
        document.getElementById('previewMessage').innerHTML = `<strong>Summary:</strong> ${data.message}`;

        if (data.valid_count > 0) {
            document.getElementById('validCount').textContent = data.valid_count;
            document.getElementById('validRowsList').innerHTML = data.valid_rows
                .map((row, idx) => `
                    <div>${idx + 1}. <strong>${row.name}</strong> (${row.email}) - Dept: ${row.department_name || 'None'}</div>
                `)
                .join('');
            document.getElementById('validRowsPreview').classList.remove('hidden');
        }

        if (data.error_count > 0) {
            document.getElementById('errorCount').textContent = data.error_count;
            document.getElementById('errorRowsList').innerHTML = data.error_rows
                .map(row => `
                    <div><strong>Line ${row.line}:</strong> ${row.data.name || row.data.email} - ${row.errors.join('; ')}</div>
                `)
                .join('');
            document.getElementById('errorRowsPreview').classList.remove('hidden');
        }
    }

    function confirmImport() {
        if (!previewedData || previewedData.length === 0) {
            setImportStatus('No valid rows to import.', 'error');
            if (window.showAlert) window.showAlert('error', 'No valid rows to import');
            return;
        }

        setImportLoading(true, 'Importing employees...');
        setImportStatus('Importing the valid rows now...', 'info');

        fetch('{{ route("bulk-import.confirm") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ valid_rows: previewedData }),
        })
        .then(async response => {
            const text = await response.text();
            let payload = {};

            try {
                payload = text ? JSON.parse(text) : {};
            } catch (error) {
                payload = { message: text || 'Invalid server response.' };
            }

            if (!response.ok) {
                throw new Error(payload.message || 'Import failed');
            }

            return payload;
        })
        .then(data => {
            setImportLoading(false);
            document.getElementById('importStep2').classList.add('hidden');
            document.getElementById('importStep3').classList.remove('hidden');

            const resultHtml = `
                <div class="text-left">
                    <h3 class="font-semibold text-lg mb-2">${data.success ? ' Import Complete!' : '❌ Import Failed'}</h3>
                    <p class="mb-3"><strong>${data.message}</strong></p>
                    <ul class="text-sm space-y-1">
                        <li>📥 Imported: <strong>${data.imported}</strong></li>
                        <li>⏭️ Skipped: <strong>${data.skipped}</strong></li>
                        ${data.errors && data.errors.length > 0 ? '<li> Errors: ' + data.errors.join('; ') + '</li>' : ''}
                    </ul>
                </div>
            `;
            document.getElementById('importResult').innerHTML = resultHtml;

            if (window.showAlert) {
                if (data.success && data.imported > 0 && data.skipped > 0) {
                    window.showAlert('warning', `${data.imported} employees imported and ${data.skipped} existing record(s) skipped.`);
                } else if (data.success && data.imported > 0) {
                    window.showAlert('success', `Successfully imported ${data.imported} employees!`);
                } else if (data.success && data.skipped > 0) {
                    window.showAlert('warning', `${data.skipped} employee record(s) were skipped because they already existed.`);
                } else if (data.success) {
                    window.showAlert('info', data.message || 'Import completed.');
                }
            }

            if (data.success && data.imported > 0) {
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        })
        .catch(error => {
            setImportLoading(false);
            setImportStatus(error.message || 'Import request failed.', 'error');
            if (window.showAlert) window.showAlert('error', 'Error: ' + error.message);
        });
    }

    function backToUpload() {
        document.getElementById('importStep2').classList.add('hidden');
        document.getElementById('importStep1').classList.remove('hidden');
        previewedData = null;
    }

    const attendance = [{
            id: 1,
            employee: 'Amina Hassan',
            date: '2026-04-30',
            checkin: '08:15',
            checkout: '17:45',
            hours: '9.5',
            status: 'Present'
        },
        {
            id: 2,
            employee: 'Joseph Kimani',
            date: '2026-04-30',
            checkin: '08:00',
            checkout: '17:00',
            hours: '9',
            status: 'Present'
        },
        {
            id: 3,
            employee: 'Lucy Mwangi',
            date: '2026-04-30',
            checkin: '',
            checkout: '',
            hours: '-',
            status: 'Absent'
        },
    ];

    const leaves = [{
            id: 1,
            employee: 'Amina Hassan',
            type: 'Annual Leave',
            from: '2026-05-01',
            to: '2026-05-05',
            days: 5,
            status: 'Pending'
        },
        {
            id: 2,
            employee: 'Joseph Kimani',
            type: 'Sick Leave',
            from: '2026-04-28',
            to: '2026-04-29',
            days: 2,
            status: 'Approved'
        },
    ];

    const payroll = [{
            id: 1,
            employee: 'Amina Hassan',
            basic: 2500000,
            deductions: 375000,
            netPay: 2125000,
            status: 'Paid'
        },
        {
            id: 2,
            employee: 'Joseph Kimani',
            basic: 1800000,
            deductions: 270000,
            netPay: 1530000,
            status: 'Pending'
        },
        {
            id: 3,
            employee: 'Lucy Mwangi',
            basic: 2200000,
            deductions: 330000,
            netPay: 1870000,
            status: 'Paid'
        },
    ];

    function switchTab(tab, btnEl) {
        document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('border-brand-600', 'text-slate-700', 'border-b-2');
            b.classList.add('text-slate-500');
        });
        const content = document.getElementById('tab-' + tab);
        if (content) content.classList.remove('hidden');
        if (!btnEl) btnEl = document.querySelector('.tab-btn');
        if (btnEl) {
            btnEl.classList.add('border-b-2', 'border-brand-600', 'text-slate-700');
            btnEl.classList.remove('text-slate-500');
        }
    }

    function openAddEmployeeModal() {
        populateDepartmentDropdown();
        const companySelect = document.getElementById('empCompany');
        if (companySelect) {
            companySelect.onchange = populateDepartmentDropdown;
        }
        document.getElementById('addEmployeeModal').classList.remove('hidden');
    }

    function closeAddEmployeeModal() {
        document.getElementById('addEmployeeModal').classList.add('hidden');
    }

    function deleteEmployee(id) {
        if (typeof window.openConfirm !== 'function') {
            return;
        }

        window.openConfirm({
            title: 'Delete employee',
            message: 'This action cannot be undone. Do you want to continue?',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            variant: 'danger',
            onConfirm: () => {
                const loader = document.getElementById('employeeDeleteLoader');
                if (loader) {
                    loader.classList.remove('hidden');
                    loader.classList.add('flex');
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/employees/' + id;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;

                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                window.setTimeout(() => form.submit(), 75);
            }
        });
    }

    function showEmployeeLoader(event) {
        if (event) {
            event.preventDefault();
        }

        const loader = document.getElementById('employeeLoader');
        const submitBtn = document.getElementById('employeeSubmitBtn');
        const form = document.getElementById('employeeForm');

        if (loader) {
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';
        }

        if (form) {
            window.setTimeout(() => {
                form.submit();
            }, 75);
        }

        return true;
    }

    function saveEmployee() {
        const name = document.getElementById('empName').value;
        if (name) {
            window.showAlert('info', 'Employee save is now backend-driven. Connect this form to a POST endpoint next.');
            closeAddEmployeeModal();
            document.getElementById('empName').value = '';
            document.getElementById('empDept').value = '';
            document.getElementById('empJoinDate').value = '';
        } else {
            window.showAlert('error', 'Full name is required.');
        }
    }

    function populateDepartmentDropdown() {
        const deptSelect = document.getElementById('empDept');
        const companySelect = document.getElementById('empCompany');
        if (!deptSelect) {
            return;
        }

        let selectedCompanyId = '';
        
        if (companySelect) {
            selectedCompanyId = companySelect.value;
        } else {
            const hiddenCompanyInput = document.querySelector('input[name="company_id"]');
            if (hiddenCompanyInput) {
                selectedCompanyId = hiddenCompanyInput.value;
            }
        }
        
        deptSelect.innerHTML = '<option value="">Select Department</option>';

        departmentOptions
            .filter(dept => !selectedCompanyId || String(dept.company_id) === String(selectedCompanyId))
            .forEach(dept => {
                const option = document.createElement('option');
                option.value = dept.id;
                option.textContent = dept.name;
                deptSelect.appendChild(option);
            });
    }

    function closeAddLeaveModal() {
        const modal = document.getElementById('addLeaveModal');
        const form = document.getElementById('leaveRequestForm');

        if (form) {
            form.reset();
        }

        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function syncLeaveDaysFromDates() {
        const fromEl = document.getElementById('leaveFrom');
        const toEl = document.getElementById('leaveTo');
        const daysEl = document.getElementById('leaveDays');

        if (!fromEl || !toEl || !daysEl || !fromEl.value || !toEl.value) {
            return;
        }

        const start = new Date(fromEl.value);
        const end = new Date(toEl.value);
        const diff = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;

        if (diff > 0) {
            daysEl.value = diff;
        }
    }

    function openAddLeaveModal() {
        const modal = document.getElementById('addLeaveModal');
        const fromEl = document.getElementById('leaveFrom');
        const toEl = document.getElementById('leaveTo');

        if (!modal) {
            return;
        }

        if (fromEl && !fromEl.dataset.boundLeaveSync) {
            fromEl.addEventListener('change', syncLeaveDaysFromDates);
            fromEl.dataset.boundLeaveSync = '1';
        }

        if (toEl && !toEl.dataset.boundLeaveSync) {
            toEl.addEventListener('change', syncLeaveDaysFromDates);
            toEl.dataset.boundLeaveSync = '1';
        }

        modal.classList.remove('hidden');
    }

    function getLeaveStatusBadge(status) {
        const text = status || 'Pending';
        if (text === 'Approved') {
            return '<span class="inline-block px-2 py-0.5 rounded bg-brand-50 text-brand-700 text-xs font-medium">Approved</span>';
        }
        if (text === 'Rejected') {
            return '<span class="inline-block px-2 py-0.5 rounded bg-red-50 text-red-700 text-xs font-medium">Rejected</span>';
        }
        return '<span class="inline-block px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-xs font-medium">Pending</span>';
    }

    function submitLeaveRequest(event) {
        if (event) {
            event.preventDefault();
        }

        const form = document.getElementById('leaveRequestForm');
        if (!form) return false;

        const type = document.getElementById('leaveType')?.value || '';
        const from = document.getElementById('leaveFrom')?.value || '';
        const to = document.getElementById('leaveTo')?.value || '';
        let days = Number(document.getElementById('leaveDays')?.value || 0);

        if (!type || !from || !to) {
            if (window.showAlert) {
                window.showAlert('error', 'Please fill all required leave fields.');
            }
            return false;
        }

        if (new Date(to) < new Date(from)) {
            if (window.showAlert) {
                window.showAlert('error', 'To Date cannot be before From Date.');
            }
            return false;
        }

        if (!days) {
            const diff = Math.floor((new Date(to) - new Date(from)) / (1000 * 60 * 60 * 24)) + 1;
            days = Math.max(1, diff);
        }

        const formData = new FormData(form);

        const loader = document.getElementById('leaveLoader');
        const messageEl = document.getElementById('leaveLoaderText');
        const submitBtn = document.getElementById('leaveSubmitBtn');

        if (loader) {
            if (messageEl) messageEl.textContent = 'Submitting leave request...';
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }
        if (submitBtn) submitBtn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(async response => {
            if (response.ok) return response.json();
            const data = await response.json().catch(() => ({}));
            if (data.errors) {
                const firstError = Object.values(data.errors)[0];
                throw new Error(Array.isArray(firstError) ? firstError[0] : 'Validation failed');
            }
            throw new Error(data.message || 'Failed to submit leave request');
        })
        .then(data => {
            if (loader) loader.classList.add('hidden');
            if (submitBtn) submitBtn.disabled = false;

            const leave = data.leave;
            const leaveTable = document.getElementById('leaveTable');

            if (leaveTable && leave) {
                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-50';
                row.id = `leave-row-${leave.id}`;
                row.innerHTML = `
                    <td class="px-4 py-3 text-sm">${leave.user?.name || 'Employee'}</td>
                    <td class="px-4 py-3 text-sm">${leave.leave_type || ''}</td>
                    <td class="px-4 py-3 text-sm">${leave.from_date || ''}</td>
                    <td class="px-4 py-3 text-sm">${leave.to_date || ''}</td>
                    <td class="px-4 py-3 text-sm">${leave.days || 0}</td>
                    <td class="px-4 py-3 text-sm">${getLeaveStatusBadge(leave.status)}</td>
                    <td class="px-4 py-3 text-sm space-x-2">
                        <button onclick="approveLeave(${leave.id})" class="px-2 py-1 bg-brand-50 text-brand-700 text-xs rounded hover:bg-brand-100">Approve</button>
                        <button onclick="rejectLeave(${leave.id})" class="px-2 py-1 bg-red-50 text-red-700 text-xs rounded hover:bg-red-100">Reject</button>
                    </td>
                `;
                leaveTable.prepend(row);
            }

            closeAddLeaveModal();

            if (window.showAlert) {
                window.showAlert('success', data.message || 'Leave request submitted successfully.');
            }
        })
        .catch(error => {
            if (loader) loader.classList.add('hidden');
            if (submitBtn) submitBtn.disabled = false;
            if (window.showAlert) {
                window.showAlert('error', error.message || 'Failed to submit leave request. Please try again.');
            }
        });

        return false;
    }

    function approveLeave(id) {
        updateLeaveStatus(id, 'Approved');
    }

    function rejectLeave(id) {
        updateLeaveStatus(id, 'Rejected');
    }

    function updateLeaveStatus(leaveId, status) {
        const formData = new FormData();
        formData.append('status', status);

        const loader = document.getElementById('leaveLoader');
        if (loader) {
            if (document.getElementById('leaveLoaderText')) {
                document.getElementById('leaveLoaderText').textContent = `${status === 'Approved' ? 'Approving' : 'Rejecting'} leave request...`;
            }
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        fetch(`/leaves/${leaveId}`, {
            method: 'PUT',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (loader) loader.classList.add('hidden');
            location.reload();
        })
        .catch(error => {
            if (loader) loader.classList.add('hidden');
            if (window.showAlert) {
                window.showAlert('error', 'Failed to update leave status. Please try again.');
            }
        });
    }

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
    }

    function openAddDepartmentModal() {
        resetDepartmentForm();
        document.getElementById('addDepartmentModal').classList.remove('hidden');
    }

    function closeAddDepartmentModal() {
        resetDepartmentForm();
        document.getElementById('addDepartmentModal').classList.add('hidden');
    }

    function showDepartmentLoader(event) {
        if (event) {
            event.preventDefault();
        }

        const loader = document.getElementById('departmentLoader');
        const submitBtn = document.getElementById('departmentSubmitBtn');
        const form = document.getElementById('departmentForm');

        if (loader) {
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';
        }

        if (form) {
            window.setTimeout(() => form.submit(), 75);
        }

        return true;
    }

    function resetDepartmentForm() {
        const form = document.getElementById('departmentForm');
        const editingId = document.getElementById('departmentEditingId');
        const title = document.getElementById('departmentModalTitle');
        const submitBtn = document.getElementById('departmentSubmitBtn');
        const loader = document.getElementById('departmentLoader');
        const deleteLoader = document.getElementById('departmentDeleteLoader');
        const companySelect = document.getElementById('deptCompany');

        if (editingId) editingId.value = '';
        if (title) title.textContent = 'Add Department';
        if (submitBtn) submitBtn.textContent = 'Save';
        if (submitBtn) submitBtn.disabled = false;
        if (companySelect) companySelect.disabled = false;
        if (loader) {
            loader.classList.add('hidden');
            loader.classList.remove('flex');
        }
        if (deleteLoader) {
            deleteLoader.classList.add('hidden');
            deleteLoader.classList.remove('flex');
        }
        if (form) {
            form.action = '{{ route('departments.store') }}';
            form.method = 'POST';
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }
            form.reset();
        }
    }

    function editDepartment(departmentData) {
        const form = document.getElementById('departmentForm');
        const editingId = document.getElementById('departmentEditingId');
        const title = document.getElementById('departmentModalTitle');
        const submitBtn = document.getElementById('departmentSubmitBtn');
        const nameInput = document.getElementById('deptName');
        const descInput = document.getElementById('deptDesc');
        const companySelect = document.getElementById('deptCompany');

        if (editingId) editingId.value = departmentData.id;
        if (title) title.textContent = 'Edit Department';
        if (submitBtn) submitBtn.textContent = 'Update';
        if (nameInput) nameInput.value = departmentData.name || '';
        if (descInput) descInput.value = departmentData.description === '-' ? '' : (departmentData.description || '');

        if (companySelect) {
            companySelect.value = departmentData.company_id || companySelect.value;
        }

        if (form) {
            form.action = '/departments/' + departmentData.id;
            form.method = 'POST';

            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
        }

        document.getElementById('addDepartmentModal').classList.remove('hidden');
    }

    function deleteDepartment(id) {
        if (typeof window.openConfirm !== 'function') {
            return;
        }

        window.openConfirm({
            title: 'Delete department',
            message: 'This action cannot be undone. Do you want to continue?',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            variant: 'danger',
            onConfirm: () => {
                const loader = document.getElementById('departmentDeleteLoader');
                if (loader) {
                    loader.classList.remove('hidden');
                    loader.classList.add('flex');
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/departments/' + id;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;

                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                window.setTimeout(() => form.submit(), 75);
            }
        });
    }

    function openRecordSalaryModal() {
        document.getElementById('recordSalaryModal').classList.remove('hidden');
    }

    function closeRecordSalaryModal() {
        const form = document.getElementById('salaryForm');
        if (form) form.reset();
        document.getElementById('editingId').value = '';
        document.getElementById('modalTitle').textContent = 'Record Salary';
        if (form) {
            form.action = '{{ route('payroll.store') }}';
            form.method = 'POST';
        }
        const methodInput = document.querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }
        const empSelect = document.querySelector('select[name="user_id"]');
        if (empSelect) {
            empSelect.disabled = false;
        }
        document.getElementById('recordSalaryModal').classList.add('hidden');
    }

    function calcNetSalary() {
        const basicEl = document.getElementById('basicSalary');
        const dedEl = document.getElementById('deductions');
        const netEl = document.getElementById('netSalary');
        if (!basicEl || !dedEl || !netEl) return;
        const basic = parseFloat(basicEl.value) || 0;
        const ded = parseFloat(dedEl.value) || 0;
        const net = Math.max(0, basic - ded);
        netEl.value = net.toFixed(2);
    }

    document.addEventListener('input', (e) => {
        if (e.target && (e.target.id === 'basicSalary' || e.target.id === 'deductions')) {
            calcNetSalary();
        }
    });

    function showSalaryLoader(event) {
        if (event) event.preventDefault();
        const loader = document.getElementById('salaryLoader');
        const submitBtn = document.getElementById('salarySubmitBtn');
        const form = document.getElementById('salaryForm');

        if (loader) {
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';
        }

        if (form) {
            window.setTimeout(() => form.submit(), 75);
        }

        return true;
    }

    function editSalary(id, salaryData) {
        document.getElementById('editingId').value = id;
        document.getElementById('modalTitle').textContent = 'Edit Salary';
        document.getElementById('basicSalary').value = salaryData.basic;
        document.getElementById('deductions').value = salaryData.deductions;
        document.getElementById('netSalary').value = salaryData.net;

        const empSelect = document.querySelector('select[name="user_id"]');
        if (empSelect) {
            let option = empSelect.querySelector(`option[value="${salaryData.user_id}"]`);
            if (!option) {
                option = document.createElement('option');
                option.value = salaryData.user_id;
                option.textContent = salaryData.employee;
                empSelect.appendChild(option);
            }
            empSelect.value = salaryData.user_id;
            empSelect.disabled = true;
        }

        const dateInput = document.querySelector('input[name="effective_date"]');
        if (dateInput && salaryData.effective_date !== '-') {
            dateInput.value = salaryData.effective_date;
        }

        const form = document.getElementById('salaryForm');
        form.action = '/payroll/' + id;
        form.method = 'POST';

        let methodInput = document.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        openRecordSalaryModal();
    }

    function deleteSalary(id) {
        if (typeof window.openConfirm !== 'function') {
            return;
        }

        window.openConfirm({
            title: 'Delete salary record',
            message: 'This action cannot be undone. Do you want to continue?',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            variant: 'danger',
            onConfirm: () => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/payroll/' + id;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;

                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function logout() {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = '/login';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        switchTab('employees', document.querySelector('.tab-btn'));
    });
</script>
