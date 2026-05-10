<script>
    // Tab switching for UI only (tables are hardcoded)
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

    // Modal helpers
    function openLocalModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');
    }

    function closeLocalModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
    }

    // Modal open handlers
    function openAddCustomerModal() {
        resetCustomerForm();
        openLocalModal('modalAddCustomer');
    }

    function resetCustomerForm() {
        const form = document.getElementById('customerForm');
        if (form) {
            form.reset();
            form.action = '{{ route("customers.store") }}';
            form.method = 'POST';
        }

        const methodField = document.getElementById('methodField');
        if (methodField) methodField.innerHTML = '';

        const modalTitle = document.getElementById('modalTitle');
        if (modalTitle) modalTitle.textContent = 'Add Customer';

        const modalSubtitle = document.getElementById('modalSubtitle');
        if (modalSubtitle) modalSubtitle.textContent = 'Create a new customer record';

        const submitBtn = document.getElementById('submitCustomerBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Customer';
        }

        window.editingCustomerId = null;
    }

    function openEditCustomerModal(customerId) {
        // Fetch customer data
        fetch(`{{ route('customers.show', ':id') }}`.replace(':id', customerId))
            .then(response => response.json())
            .then(customer => {
                window.editingCustomerId = customerId;

                // Update modal title and subtitle
                const modalTitle = document.getElementById('modalTitle');
                if (modalTitle) modalTitle.textContent = 'Edit Customer';

                const modalSubtitle = document.getElementById('modalSubtitle');
                if (modalSubtitle) modalSubtitle.textContent = 'Update customer information';

                // Update form action to PUT route
                const form = document.getElementById('customerForm');
                if (form) {
                    form.action = `{{ url('customers') }}/${customerId}`;
                }

                // Add PUT method override
                const methodField = document.getElementById('methodField');
                if (methodField) {
                    methodField.innerHTML = '@method("PUT")';
                }

                // Populate form fields
                document.getElementById('customer_type').value = customer.customer_type || '';
                document.getElementById('customer_company').value = customer.company_id || '';
                document.getElementById('customer_name').value = customer.customer_name || '';
                document.getElementById('customer_phone').value = customer.phone_number || '';
                document.getElementById('customer_whatsapp').value = customer.whatsapp_number || '';
                document.getElementById('customer_email').value = customer.email || '';
                document.getElementById('customer_region').value = customer.region || '';
                document.getElementById('customer_district').value = customer.district || '';
                document.getElementById('customer_address').value = customer.street_address || '';
                document.getElementById('customer_source').value = customer.customer_source || '';
                document.getElementById('customer_price_category').value = customer.price_category || '';
                document.getElementById('customer_payment_terms').value = customer.payment_terms || '';
                document.getElementById('customer_payment_method').value = customer.preferred_payment_method || '';
                document.getElementById('customer_status').value = customer.status || 'Active';
                document.getElementById('customer_notes').value = customer.notes || '';

                // Update submit button
                const submitBtn = document.getElementById('submitCustomerBtn');
                if (submitBtn) {
                    submitBtn.textContent = 'Update Customer';
                    submitBtn.disabled = false;
                }

                // Open modal
                openLocalModal('modalAddCustomer');
            })
            .catch(error => {
                console.error('Error fetching customer:', error);
                window.showAlert('error', 'Failed to load customer data');
            });
    }

    function openAddOrderModal() {
        document.getElementById('orderForm')?.reset();
        openLocalModal('modalAddOrder');
    }

    function openAddContractModal() {
        document.getElementById('contractForm')?.reset();
        openLocalModal('modalAddContract');
    }

    function submitAddCustomer(e) {
        return true;
    }

    function showCustomerCreateLoader(e) {
        const loader = document.getElementById('customerCreateLoader');
        const submitBtn = document.getElementById('submitCustomerBtn');

        const isEditing = window.editingCustomerId !== null && window.editingCustomerId !== undefined;
        const message = isEditing ? 'Updating customer...' : 'Saving customer...';
        const buttonText = isEditing ? 'Updating...' : 'Saving...';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = buttonText;
        }

        if (loader) {
            // Update loader message dynamically
            const messageEl = document.getElementById('customerCreateLoaderText');
            if (messageEl) {
                messageEl.textContent = message;
            }
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        return true;
    }

    function submitAddOrder(e) {
        e.preventDefault();
        window.showAlert('info', 'Order form submitted');
        closeLocalModal('modalAddOrder');
        return false;
    }

    function submitAddContract(e) {
        e.preventDefault();
        window.showAlert('info', 'Contract form submitted');
        closeLocalModal('modalAddContract');
        return false;
    }

    function toggleCustomerDetails(customerId, buttonEl) {
        const targetRow = document.getElementById(`customer-details-${customerId}`);
        if (!targetRow) return;

        const shouldOpen = targetRow.classList.contains('hidden');

        document.querySelectorAll('[id^="customer-details-"]').forEach(row => {
            row.classList.add('hidden');
        });

        if (shouldOpen) {
            targetRow.classList.remove('hidden');
        }
    }

    function confirmDeleteCustomer(customerId, customerName) {
        openConfirm({
            title: 'Delete Customer',
            message: `Are you sure you want to delete "${customerName}"? This action cannot be undone.`,
            confirmText: 'Delete',
            cancelText: 'Cancel',
            variant: 'danger',
            onConfirm: function() {
                deleteCustomer(customerId);
            }
        });
    }

    function deleteCustomer(customerId) {
        const loader = document.getElementById('customerCreateLoader');
        const messageEl = document.getElementById('customerCreateLoaderText');

        if (loader) {
            if (messageEl) messageEl.textContent = 'Deleting customer...';
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        fetch(`{{ url('customers') }}/${customerId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                return response.json().catch(() => ({}));
            }
            throw new Error('Failed to delete customer');
        })
        .then(() => {
            window.location.reload();
        })
        .catch(error => {
            console.error('Error deleting customer:', error);
            if (loader) loader.classList.add('hidden');
            window.showAlert('error', 'Failed to delete customer. Please try again.');
        });
    }

    // Initialize tab on page load
    document.addEventListener('DOMContentLoaded', () => { switchTab('customers', document.querySelector('.tab-btn')); });
</script>
