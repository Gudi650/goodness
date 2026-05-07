<script>
    function renderButton(label, onclick) {
        return `<button onclick="${onclick}" class="w-full lg:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
                    ${label}
                </button>`;
    }

    function togglePane(activeTab) {
        const panes = {
            invoices: document.getElementById('invoicesPane'),
            expenses: document.getElementById('expensesPane'),
            payments: document.getElementById('paymentsPane'),
        };

        Object.entries(panes).forEach(([tab, pane]) => {
            if (!pane) return;
            pane.classList.toggle('hidden', tab !== activeTab);
        });
    }

    function openAddInvoiceModal() {
        const body = document.getElementById('addInvoiceModal').innerHTML;

        window.openModal('Add Invoice', body, () => {
            const id = document.getElementById('invoiceId').value.trim();
            const company = document.getElementById('invoiceCompany').value.trim();
            const amount = parseFloat(document.getElementById('invoiceAmount').value);

            if (!id) {
                window.showAlert('error', 'Invoice ID is required');
                return false;
            }

            if (!company) {
                window.showAlert('error', 'Company is required');
                return false;
            }

            if (!amount || amount <= 0) {
                window.showAlert('error', 'Amount must be greater than 0');
                return false;
            }

            window.showAlert('success', 'Invoice added successfully');
            return true;
        });
    }

    function openAddExpenseModal() {
        const body = document.getElementById('addExpenseModal').innerHTML;

        window.openModal('Add Expense', body, () => {
            const id = document.getElementById('expenseId').value.trim();
            const category = document.getElementById('expenseCategory').value.trim();
            const amount = parseFloat(document.getElementById('expenseAmount').value);
            const company = document.getElementById('expenseCompany').value;
            const department = document.getElementById('expenseDepartment').value;
            const recordedBy = document.getElementById('expenseRecordedBy').value;
            const paymentMethod = document.getElementById('expensePaymentMethod').value;
            const date = document.getElementById('expenseDate').value;
            const mode = document.getElementById('expenseSubmitMode')?.value || 'submit';

            if (!id) {
                window.showAlert('error', 'Expense number is required');
                return false;
            }

            if (!date) {
                window.showAlert('error', 'Expense date is required');
                return false;
            }

            if (!company) {
                window.showAlert('error', 'Company is required');
                return false;
            }

            if (!department) {
                window.showAlert('error', 'Department is required');
                return false;
            }

            if (!recordedBy) {
                window.showAlert('error', 'Recorded By is required');
                return false;
            }

            if (!category) {
                window.showAlert('error', 'Category is required');
                return false;
            }

            if (!paymentMethod) {
                window.showAlert('error', 'Payment method is required');
                return false;
            }

            if (!amount || amount <= 0) {
                window.showAlert('error', 'Amount must be greater than 0');
                return false;
            }

            window.showAlert('success', mode === 'draft' ? 'Expense saved as draft successfully' : 'Expense submitted successfully');
            return true;
        }, {
            widthClass: 'max-w-6xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]'
        });

        initializeExpenseModalForm();
    }

    function setExpenseSubmitMode(mode) {
        const input = document.getElementById('expenseSubmitMode');
        if (input) {
            input.value = mode === 'draft' ? 'draft' : 'submit';
        }
    }

    function initializeExpenseModalForm() {
        const today = new Date().toISOString().split('T')[0];
        const random = Math.floor(Math.random() * 9000) + 1000;

        const expenseId = document.getElementById('expenseId');
        const expenseDate = document.getElementById('expenseDate');
        const submitMode = document.getElementById('expenseSubmitMode');

        if (expenseId && !expenseId.value) {
            expenseId.value = `EXP-${random}`;
        }
        if (expenseDate && !expenseDate.value) {
            expenseDate.value = today;
        }
        if (submitMode) {
            submitMode.value = 'submit';
        }

        bindExpenseCategoryOptions();
        bindExpenseVatCalculations();
        bindExpenseFilePreview();
    }

    function bindExpenseCategoryOptions() {
        const map = {
            Operational: ['Rent', 'Utilities', 'Internet', 'Office Supplies', 'Cleaning'],
            Payroll: ['Salaries', 'Overtime', 'Allowances', 'Statutory (NSSF/PAYE)'],
            Travel: ['Fuel', 'Transport', 'Accommodation', 'Per Diem'],
            Procurement: ['Stock Purchase', 'Equipment', 'Raw Materials'],
            Marketing: ['Advertising', 'Events', 'Branding'],
            Maintenance: ['Vehicle', 'Equipment', 'Building'],
            Miscellaneous: ['Other'],
        };

        const category = document.getElementById('expenseCategory');
        const subCategory = document.getElementById('expenseSubCategory');
        if (!category || !subCategory) return;

        const syncSubCategories = () => {
            const options = map[category.value] || [];
            subCategory.innerHTML = '<option value="">Select sub-category...</option>';
            options.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item;
                opt.textContent = item;
                subCategory.appendChild(opt);
            });
        };

        category.addEventListener('change', syncSubCategories);
        syncSubCategories();
    }

    function bindExpenseVatCalculations() {
        const amountInput = document.getElementById('expenseAmount');
        const vatToggle = document.getElementById('expenseVatIncluded');
        const vatRate = document.getElementById('expenseVatRate');
        const vatAmount = document.getElementById('expenseVatAmount');
        const netAmount = document.getElementById('expenseNetAmount');
        const vatRateWrap = document.getElementById('expenseVatRateWrap');
        const vatAmountWrap = document.getElementById('expenseVatAmountWrap');

        if (!amountInput || !vatToggle || !vatRate || !vatAmount || !netAmount || !vatRateWrap || !vatAmountWrap) return;

        const recalc = () => {
            const gross = parseFloat(amountInput.value) || 0;
            const rate = parseFloat(vatRate.value) || 0;
            const hasVat = vatToggle.checked;

            vatRateWrap.classList.toggle('hidden', !hasVat);
            vatAmountWrap.classList.toggle('hidden', !hasVat);

            if (!hasVat || rate <= 0) {
                vatAmount.value = '0.00';
                netAmount.value = gross.toFixed(2);
                return;
            }

            const vat = gross * (rate / (100 + rate));
            const net = gross - vat;
            vatAmount.value = vat.toFixed(2);
            netAmount.value = net.toFixed(2);
        };

        amountInput.addEventListener('input', recalc);
        vatToggle.addEventListener('change', recalc);
        vatRate.addEventListener('input', recalc);
        recalc();
    }

    function bindExpenseFilePreview() {
        const input = document.getElementById('expenseAttachment');
        const preview = document.getElementById('expenseFilePreview');
        if (!input || !preview) return;

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            if (!file) {
                preview.textContent = 'No file selected';
                return;
            }

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML = `<div class="flex items-center gap-3"><img src="${e.target.result}" alt="preview" class="w-12 h-12 object-cover rounded border border-slate-200"><span class="text-slate-700">${file.name}</span></div>`;
                };
                reader.readAsDataURL(file);
                return;
            }

            preview.textContent = file.name;
        });
    }

    function openAddPaymentModal() {
        const body = document.getElementById('addPaymentModal').innerHTML;

        window.openModal('Add Payment', body, () => {
            const id = document.getElementById('paymentId').value.trim();
            const company = document.getElementById('paymentCompany').value.trim();
            const amount = parseFloat(document.getElementById('paymentAmount').value);

            if (!id) {
                window.showAlert('error', 'Payment ID is required');
                return false;
            }

            if (!company) {
                window.showAlert('error', 'Company is required');
                return false;
            }

            if (!amount || amount <= 0) {
                window.showAlert('error', 'Amount must be greater than 0');
                return false;
            }

            window.showAlert('success', 'Payment added successfully');
            return true;
        });
    }

    function switchTab(tab, btnEl) {
        // Get all tab buttons
        const tabBtns = document.querySelectorAll('.tab-btn');
        
        // Remove active state from all buttons
        tabBtns.forEach(btn => {
            btn.classList.remove('text-slate-700', 'border-brand-600','border-b-2');
            btn.classList.add('text-slate-500');
        });

        // Add active state to clicked button
        if (btnEl) {
            btnEl.classList.remove('text-slate-500');
            btnEl.classList.add('text-slate-700', 'border-brand-600','border-b-2');
        }

        // Toggle content panes
        togglePane(tab);

        // Update section title and action button
        const sectionTitle = document.getElementById('sectionTitle');
        const actionButton = document.getElementById('actionButton');

        if (tab === 'invoices') {
            sectionTitle.textContent = 'Invoices';
            actionButton.innerHTML = renderButton('Add Invoice', 'openInvoiceModal()');
            return;
        }

        if (tab === 'expenses') {
            sectionTitle.textContent = 'Expenses';
            actionButton.innerHTML = renderButton('Add Expense', 'openAddExpenseModal()');
            return;
        }

        sectionTitle.textContent = 'Payments';
        actionButton.innerHTML = renderButton('Add Payment', 'openAddPaymentModal()');
    }

    function deleteInvoice(id) {
        if (typeof window.openConfirm !== 'function') {
            return;
        }

        window.openConfirm({
            title: 'Delete invoice',
            message: 'This action cannot be undone. Do you want to continue?',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            variant: 'danger',
            onConfirm: () => {
                // Show loader if function exists (from invoices.blade.php)
                if (typeof showInvoiceLoader === 'function') {
                    showInvoiceLoader();
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/invoices/' + id;
                form.style.display = 'none';

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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        switchTab('invoices', document.querySelector('.tab-btn'));
    });
</script>
