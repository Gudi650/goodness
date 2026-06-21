<script>
    //rendering the button with onclick function ready to go
    function renderButton(label, onclick) {
        return `<button onclick="${onclick}" class="w-full lg:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
                    ${label}
                </button>`;
    }

    //function to render the section button if needed
    function renderSectionButton(label, onclick) {
        return `<button onclick="${onclick}" class="w-full lg:w-auto flex-shrink-0 whitespace-nowrap px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white rounded-md text-sm font-medium transition-colors">
                    ${label}
                </button>`;
    }

    // Toggle visibility of content panes based on active tab
    function togglePane(activeTab) {
        const panes = {
            invoices: document.getElementById('invoicesPane'),
            expenses: document.getElementById('expensesPane'),
            payments: document.getElementById('paymentsPane'),
            accounts: document.getElementById('accountsPane'),
            assets: document.getElementById('assetsPane'),
            liabilities: document.getElementById('liabilitiesPane'),
            items: document.getElementById('itemsPane'),
        };

        Object.entries(panes).forEach(([tab, pane]) => {
            if (!pane) return;
            pane.classList.toggle('hidden', tab !== activeTab);
        });
    }

    //open add invoice modal
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

    //open add expense modal
    function openAddExpenseModal() {
        const body = document.getElementById('addExpenseModal').innerHTML;

        window.openModal('Add Expense', body, null, {
            widthClass: 'max-w-6xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });

        // Use requestAnimationFrame to ensure form is initialized after DOM is rendered
        requestAnimationFrame(initializeExpenseModalForm);
    }


    //open add account modal
    function openAddAccountModal() {
        const body = document.getElementById('addAccountModal').innerHTML;

        window.openModal('Add Account', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });

    }

    //open add assets modal
    function openAddAssetsModal() {
        const body = document.getElementById('addAssetsModal').innerHTML;

        //for now we should know if this is called or what , we will console log to check if this is called or not
        console.log('openAddAssetsModal called');

        window.openModal('Add Asset', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });

    }

    //open add liabilities modal
    function openAddLiabilityModal() {
        const body = document.getElementById('addLiabilitiesModal').innerHTML;

        window.openModal('Add Liability', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });
    }

    //open add category assets modal
    function openAddAssetsCategoryModal() {
        const body = document.getElementById('addAssetsCategoryModal').innerHTML;

        window.openModal('Add Asset Category', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });
    }

    //open add category liabilities modal
    function openAddLiabilityCategoryModal() {
        const body = document.getElementById('addLiabilityCategoryModal').innerHTML;

        window.openModal('Add Liability Category', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });
    }

    //open add category items modal
    function openAddItemCategoryModal() {
        const body = document.getElementById('addItemCategoryModal').innerHTML;

        window.openModal('Add Item Category', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });
    }

    //open add items modal
    function openAddItemModal() {
        const body = document.getElementById('addItemsModal').innerHTML;

        window.openModal('Add Items', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });
    }

    //function to open issue expenses modal
    function openIssueExpensesModal() {
        const body = document.getElementById('issueExpensesModal').innerHTML;

        window.openModal('Issue Expense', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });
    }


    // Helper to get form fields either from modal or main page (for expenses which have dynamic modal fields)
    function getExpenseField(id) {
        const modalBody = document.getElementById('modal-body');
        if (modalBody) {
            const inModal = modalBody.querySelector(`#${id}`);
            if (inModal) return inModal;
        }
        return document.getElementById(id);
    }

    function initializeExpenseModalForm() {
        const today = new Date().toISOString().split('T')[0];
        const random = Math.floor(Math.random() * 9000) + 1000;

        const expenseId = getExpenseField('expenseId');
        const expenseDate = getExpenseField('expenseDate');

        if (expenseId && !expenseId.value) {
            expenseId.value = `EXP-${random}`;
        }
        if (expenseDate && !expenseDate.value) {
            expenseDate.value = today;
        }

        bindExpenseCategoryOptions();
        bindExpenseDepartmentFilter();
        bindExpenseVatCalculations();
        bindExpenseFilePreview();
    }

    function bindExpenseDepartmentFilter() {
        const company = getExpenseField('expenseCompany');
        const department = getExpenseField('expenseDepartment');
        if (!company || !department) return;

        const allOptions = Array.from(department.querySelectorAll('option')).map(option => ({
            value: option.value,
            label: option.textContent,
            companyId: option.dataset.companyId || '',
        }));

        const syncDepartments = () => {
            const selectedCompanyId = company.value;
            const previousValue = department.value;

            department.innerHTML = '<option value="">Select department...</option>';

            allOptions.forEach(option => {
                if (!option.value) return;

                if (!selectedCompanyId || !option.companyId || option.companyId === selectedCompanyId) {
                    const opt = document.createElement('option');
                    opt.value = option.value;
                    opt.textContent = option.label;
                    if (option.companyId) {
                        opt.dataset.companyId = option.companyId;
                    }
                    department.appendChild(opt);
                }
            });

            // Preserve selection only if it still belongs to selected company.
            if (previousValue && Array.from(department.options).some(opt => opt.value === previousValue)) {
                department.value = previousValue;
            }
        };

        company.addEventListener('change', syncDepartments);
        syncDepartments();
    }


    //binding the categories with respective subcategries
    function bindExpenseCategoryOptions() {
        // items data injected from Laravel
        const map = @json($itemData);
        console.log("Items data:", map);

        const category = getExpenseField('expenseCategory');
        const subCategory = getExpenseField('expenseSubCategory');

        if (!category || !subCategory) {
            console.log("Category or SubCategory field not found");
            return;
        }

        const syncSubCategories = () => {
            const selectedName = category.value;
            console.log("Selected category value:", selectedName);

            // Use filter instead of find → returns all items in that category
            const selectedItems = map.filter(item => item.category_name == selectedName);
            console.log("Matched items:", selectedItems);

            // Reset subCategory dropdown
            subCategory.innerHTML = '<option value="">Select sub-category...</option>';

            // Loop through all matched items and add them as options
            selectedItems.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id; // Assuming you want to use item id as the value.
                opt.textContent = item.item_name;
                subCategory.appendChild(opt);
            });
        };

        category.addEventListener('change', syncSubCategories);
        syncSubCategories();
    }


    function bindExpenseVatCalculations() {
        const amountInput = getExpenseField('expenseAmount');
        const vatToggle = getExpenseField('expenseVatIncluded');
        const vatRate = getExpenseField('expenseVatRate');
        const vatAmount = getExpenseField('expenseVatAmount');
        const netAmount = getExpenseField('expenseNetAmount');
        const vatRateWrap = getExpenseField('expenseVatRateWrap');
        const vatAmountWrap = getExpenseField('expenseVatAmountWrap');

        if (!amountInput || !vatToggle || !vatRate || !vatAmount || !netAmount || !vatRateWrap || !vatAmountWrap)
            return;

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
        const input = getExpenseField('expenseAttachment');
        const preview = getExpenseField('expenseFilePreview');
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
                    preview.innerHTML =
                        `<div class="flex items-center gap-3"><img src="${e.target.result}" alt="preview" class="w-12 h-12 object-cover rounded border border-slate-200"><span class="text-slate-700">${file.name}</span></div>`;
                };
                reader.readAsDataURL(file);
                return;
            }

            preview.textContent = file.name;
        });
    }

    function toggleExpenseDetails(detailRowId, buttonEl) {
        const detailRow = document.getElementById(detailRowId);
        if (!detailRow) return;

        detailRow.classList.toggle('hidden');

        if (buttonEl) {
            buttonEl.classList.toggle('bg-slate-100');
            buttonEl.classList.toggle('text-brand-700');
        }
    }

    //function to open add payment modal
    function openAddPaymentModal() {
        // Use the dedicated payment modal shell so the richer layout and dynamic fields work correctly.
        if (typeof window.openPaymentModal === 'function') {
            window.openPaymentModal();
        }
    }

    //switch tab navigation
    function switchTab(tab, btnEl) {
        // Get all tab buttons
        const tabBtns = document.querySelectorAll('.tab-btn');

        // Remove active state from all buttons
        tabBtns.forEach(btn => {
            btn.classList.remove('text-slate-700', 'border-brand-600', 'border-b-2');
            btn.classList.add('text-slate-500');
        });

        // Add active state to clicked button
        if (btnEl) {
            btnEl.classList.remove('text-slate-500');
            btnEl.classList.add('text-slate-700', 'border-brand-600', 'border-b-2');
        }

        // Toggle content panes
        togglePane(tab);

        // Update section title and action button
        const sectionTitle = document.getElementById('sectionTitle');
        const actionButton = document.getElementById('actionButton');

        if (tab === 'invoices') {
            sectionTitle.textContent = 'Invoices';
            actionButton.innerHTML = renderButton('Add Invoice', 'openInvoiceModal()');
            sectionButton.classList.add('hidden');

            return;
        }

        if (tab === 'expenses') {
            sectionTitle.textContent = 'Expenses';
            actionButton.innerHTML = renderButton('Add Expense', 'openAddExpenseModal()');
            sectionButton.classList.add('hidden');
            return;
        }

        if (tab === 'accounts') {
            sectionTitle.textContent = 'Virtual Accounts Management';
            actionButton.innerHTML = renderButton('Add Account', 'openAddAccountModal()');
            sectionButton.classList.add('hidden');
            return;
        }

        if (tab === 'assets') {
            sectionTitle.textContent = 'Assets';
            actionButton.innerHTML = renderButton('Add Asset', 'openAddAssetsModal()');
            sectionButton.classList.remove('hidden');
            sectionButton.innerHTML = renderSectionButton('Add Categories', 'openAddAssetsCategoryModal()');
            return;
        }

        if (tab === 'liabilities') {
            sectionTitle.textContent = 'Liabilities';
            actionButton.innerHTML = renderButton('Add Liability', 'openAddLiabilityModal()');
            sectionButton.innerHTML = renderSectionButton('Add Categories', 'openAddLiabilityCategoryModal()');
            sectionButton.classList.remove('hidden');
            return;
        }
        if (tab === 'items') {
            sectionTitle.textContent = 'Items';
            actionButton.innerHTML = renderButton('Add Item', 'openAddItemModal()');
            sectionButton.innerHTML = renderSectionButton('Add Categories', 'openAddItemCategoryModal()');
            sectionButton.classList.remove('hidden');
            return;
        }

        sectionTitle.textContent = 'Payments';
        actionButton.innerHTML = renderButton('Add Payment', 'openAddPaymentModal()');
        sectionButton.classList.add('hidden');
    }

    //function to delete the invoice with confirmation
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
