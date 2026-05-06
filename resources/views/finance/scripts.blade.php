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

            if (!id) {
                window.showAlert('error', 'Expense ID is required');
                return false;
            }

            if (!category) {
                window.showAlert('error', 'Category is required');
                return false;
            }

            if (!amount || amount <= 0) {
                window.showAlert('error', 'Amount must be greater than 0');
                return false;
            }

            window.showAlert('success', 'Expense added successfully');
            return true;
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
