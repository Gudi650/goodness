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

    window.orderProductOptionsHtml = document.querySelector('#orderItemsBody .order-product')?.innerHTML || '<option value="">-- Select Product --</option>';

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
        fetch(`{{ url('customers') }}/${customerId}`)
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
        resetOrderForm();
        openLocalModal('modalAddOrder');
    }

    function resetOrderForm() {
        const form = document.getElementById('orderForm');
        if (form) {
            form.reset();
        }

        const orderDate = document.getElementById('order_date');
        if (orderDate) {
            orderDate.value = new Date().toISOString().split('T')[0];
        }

        const expectedDeliveryDate = document.getElementById('expected_delivery_date');
        if (expectedDeliveryDate) {
            expectedDeliveryDate.value = '';
        }

        const paymentDate = document.getElementById('order_payment_date');
        if (paymentDate) {
            paymentDate.value = new Date().toISOString().split('T')[0];
        }

        const creditDueDate = document.getElementById('order_credit_due_date');
        if (creditDueDate) {
            creditDueDate.value = '';
        }

        const shippingAddress = document.getElementById('order_shipping_address');
        if (shippingAddress) {
            shippingAddress.value = '';
        }

        const sameAsBilling = document.getElementById('same_as_billing');
        if (sameAsBilling) {
            sameAsBilling.checked = false;
        }

        const creditTermsWrap = document.getElementById('creditTermsWrap');
        if (creditTermsWrap) {
            creditTermsWrap.classList.add('hidden');
        }

        const filePreview = document.getElementById('orderFilePreview');
        if (filePreview) {
            filePreview.textContent = 'No file selected.';
        }

        const submitBtn = document.getElementById('submitOrderBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Order';
        }

        const body = document.getElementById('orderItemsBody');
        if (body) {
            body.innerHTML = '';
            body.appendChild(createOrderItemRow(1, true));
        }

        recalculateOrderTotals();
    }

    function createOrderItemRow(index, isFirst = false) {
        const row = document.createElement('tr');
        row.className = 'order-item-row';
        row.innerHTML = `
            <td class="px-3 py-2 text-sm text-slate-600 item-number">${index}</td>
            <td class="px-3 py-2">
                <select class="order-product block w-full border border-slate-200 rounded p-2 text-sm" onchange="handleOrderProductChange(this)">
                    ${window.orderProductOptionsHtml || '<option value="">-- Select Product --</option>'}
                </select>
            </td>
            <td class="px-3 py-2">
                <input type="text" class="order-sku block w-full border border-slate-200 rounded p-2 text-sm bg-slate-50" readonly />
            </td>
            <td class="px-3 py-2">
                <input type="text" class="order-description block w-full border border-slate-200 rounded p-2 text-sm" />
            </td>
            <td class="px-3 py-2">
                <input type="number" min="1" value="1" class="order-qty block w-full border border-slate-200 rounded p-2 text-sm" oninput="recalculateOrderTotals()" />
            </td>
            <td class="px-3 py-2">
                <select class="order-uom block w-full border border-slate-200 rounded p-2 text-sm">
                    <option value="Piece">Piece</option>
                    <option value="Box">Box</option>
                    <option value="Kg">Kg</option>
                    <option value="Litre">Litre</option>
                </select>
            </td>
            <td class="px-3 py-2">
                <input type="number" min="0" value="0" class="order-unit-price block w-full border border-slate-200 rounded p-2 text-sm" oninput="recalculateOrderTotals()" />
            </td>
            <td class="px-3 py-2">
                <input type="number" min="0" value="0" class="order-discount block w-full border border-slate-200 rounded p-2 text-sm" oninput="recalculateOrderTotals()" />
            </td>
            <td class="px-3 py-2">
                <input type="text" class="order-total block w-full border border-slate-200 rounded p-2 text-sm bg-slate-50" readonly value="0.00" />
            </td>
            <td class="px-3 py-2">
                <div class="space-y-1">
                    <div class="order-stock text-xs text-slate-500">Available: 0</div>
                    <div class="order-stock-warning text-xs text-red-600 hidden">Only 0 units available</div>
                </div>
            </td>
            <td class="px-3 py-2 text-center">
                <button type="button" class="remove-order-item inline-flex h-9 w-9 items-center justify-center rounded border border-slate-200 text-red-600 hover:bg-red-50 ${isFirst ? 'hidden' : ''}" onclick="removeOrderItemRow(this)">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-6 0h6" />
                    </svg>
                </button>
            </td>
        `;
        return row;
    }

    function addOrderItemRow() {
        const body = document.getElementById('orderItemsBody');
        if (!body) return;

        const nextIndex = body.querySelectorAll('.order-item-row').length + 1;
        const row = createOrderItemRow(nextIndex, false);
        const firstRowRemoveBtn = body.querySelector('.order-item-row .remove-order-item');
        if (firstRowRemoveBtn) {
            firstRowRemoveBtn.classList.remove('hidden');
        }
        body.appendChild(row);
    }

    function removeOrderItemRow(button) {
        const row = button.closest('.order-item-row');
        const body = document.getElementById('orderItemsBody');
        if (!row || !body) return;

        if (body.querySelectorAll('.order-item-row').length === 1) return;

        row.remove();
        refreshOrderItemNumbers();
        recalculateOrderTotals();
    }

    function refreshOrderItemNumbers() {
        document.querySelectorAll('#orderItemsBody .order-item-row').forEach((row, index) => {
            const numberCell = row.querySelector('.item-number');
            if (numberCell) numberCell.textContent = index + 1;
            const removeBtn = row.querySelector('.remove-order-item');
            if (removeBtn) removeBtn.classList.toggle('hidden', index === 0);
        });
    }

    function handleOrderProductChange(select) {
        const row = select.closest('.order-item-row');
        if (!row) return;

        const sku = row.querySelector('.order-sku');
        const unitPrice = row.querySelector('.order-unit-price');
        const uom = row.querySelector('.order-uom');
        const stock = row.querySelector('.order-stock');
        const warning = row.querySelector('.order-stock-warning');

        const productId = select.value;
        if (!productId) {
            if (sku) sku.value = '';
            if (unitPrice) unitPrice.value = 0;
            if (uom) uom.value = 'Piece';
            if (stock) stock.textContent = 'Available: 0';
            if (warning) warning.classList.add('hidden');
            row.dataset.availableStock = 0;
            recalculateOrderTotals();
            return;
        }

        fetch(`{{ url('products') }}/${productId}`)
            .then(r => r.json())
            .then(data => {
                if (sku) sku.value = data.sku || '';
                if (unitPrice) unitPrice.value = (data.selling_price ?? 0);
                if (uom) uom.value = data.unit_of_measure || 'Piece';
                const available = Number(data.stock ?? 0);
                if (stock) stock.textContent = `Available: ${available}`;
                if (warning) warning.classList.toggle('hidden', false);
                // store available stock on row for later checks
                row.dataset.availableStock = available;

                // hide warning initially unless qty exceeds stock
                const qty = parseFloat(row.querySelector('.order-qty')?.value || '0');
                if (warning) warning.classList.toggle('hidden', qty <= available || !qty);

                recalculateOrderTotals();
            })
            .catch(err => {
                console.error('Failed to fetch product details', err);
                if (stock) stock.textContent = 'Available: 0';
                if (warning) warning.classList.add('hidden');
                row.dataset.availableStock = 0;
                recalculateOrderTotals();
            });
    }

    function handleOrderCustomerChange(select) {
        const customerId = select.value;
        const nameField = document.getElementById('order_customer_name');
        const phoneField = document.getElementById('order_customer_phone');
        const emailField = document.getElementById('order_customer_email');
        const billingField = document.getElementById('order_billing_address');
        const shippingField = document.getElementById('order_shipping_address');
        const sameAsBilling = document.getElementById('same_as_billing');

        if (!customerId) {
            if (nameField) nameField.value = '';
            if (phoneField) phoneField.value = '';
            if (emailField) emailField.value = '';
            if (billingField) billingField.value = '';
            if (shippingField && sameAsBilling && sameAsBilling.checked) shippingField.value = '';
            return;
        }

        fetch(`{{ url('customers') }}/${customerId}`)
            .then(r => r.json())
            .then(data => {
                if (nameField) nameField.value = data.customer_name || '';
                if (phoneField) phoneField.value = data.phone_number || '';
                if (emailField) emailField.value = data.email || '';

                // Prefer street_address, fallback to region/district
                let billing = data.street_address || '';
                if (!billing) {
                    const parts = [];
                    if (data.region) parts.push(data.region);
                    if (data.district) parts.push(data.district);
                    billing = parts.join(', ');
                }

                if (billingField) billingField.value = billing;

                // If user checked same-as-billing, copy to shipping
                if (shippingField && sameAsBilling && sameAsBilling.checked) {
                    shippingField.value = billing;
                }
            })
            .catch(err => {
                console.error('Failed to fetch customer details', err);
            });
    }

    function toggleShippingAddress(checkbox) {
        const billing = document.getElementById('order_billing_address');
        const shipping = document.getElementById('order_shipping_address');
        if (!shipping) return;

        if (checkbox.checked && billing) {
            shipping.value = billing.value;
            shipping.readOnly = true;
        } else {
            shipping.readOnly = false;
        }
    }

    function toggleCreditTerms(paymentMethod) {
        const wrap = document.getElementById('creditTermsWrap');
        if (!wrap) return;

        wrap.classList.toggle('hidden', paymentMethod !== 'Credit');
        calculateCreditDueDate();
    }

    function calculateCreditDueDate() {
        const orderDate = document.getElementById('order_date')?.value;
        const dueDateField = document.getElementById('order_credit_due_date');
        const paymentMethod = document.getElementById('order_payment_method')?.value;
        const terms = parseInt(document.getElementById('order_credit_terms')?.value || '0', 10);

        if (!dueDateField || paymentMethod !== 'Credit' || !orderDate) {
            if (dueDateField) dueDateField.value = '';
            return;
        }

        const dueDate = new Date(orderDate);
        dueDate.setDate(dueDate.getDate() + terms);
        dueDateField.value = dueDate.toISOString().split('T')[0];
    }

    function recalculateOrderTotals() {
        const rows = document.querySelectorAll('#orderItemsBody .order-item-row');
        let subtotal = 0;

        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.order-qty')?.value || '0');
            const unitPrice = parseFloat(row.querySelector('.order-unit-price')?.value || '0');
            const discount = parseFloat(row.querySelector('.order-discount')?.value || '0');
            const stockWarning = row.querySelector('.order-stock-warning');
            const stockText = row.querySelector('.order-stock');
            const availableStock = Number(row.dataset.availableStock ?? 0);

            const lineGross = qty * unitPrice;
            const lineDiscount = lineGross * (discount / 100);
            const lineTotal = Math.max(lineGross - lineDiscount, 0);

            subtotal += lineTotal;

            const totalField = row.querySelector('.order-total');
            if (totalField) {
                totalField.value = lineTotal.toFixed(2);
            }

            if (stockText) stockText.textContent = `Available: ${availableStock}`;
            if (stockWarning) {
                stockWarning.textContent = `Only ${availableStock} units available`;
                stockWarning.classList.toggle('hidden', qty <= availableStock || !qty);
            }
        });

        const overallDiscountPercent = parseFloat(document.getElementById('order_discount_percent')?.value || '0');
        const vatEnabled = document.getElementById('vat_enabled')?.checked ?? true;
        const vatPercent = parseFloat(document.getElementById('order_vat_percent')?.value || '0');
        const shippingCost = parseFloat(document.getElementById('order_shipping_cost')?.value || '0');
        const otherCharges = parseFloat(document.getElementById('order_other_charges')?.value || '0');
        const amountPaid = parseFloat(document.getElementById('order_amount_paid')?.value || '0');
        const commissionPercent = parseFloat(document.getElementById('order_commission_percent')?.value || '0');

        const discountAmount = subtotal * (overallDiscountPercent / 100);
        const taxableSubtotal = Math.max(subtotal - discountAmount, 0);
        const vatAmount = vatEnabled ? taxableSubtotal * (vatPercent / 100) : 0;
        const grandTotal = taxableSubtotal + vatAmount + shippingCost + otherCharges;
        const balanceDue = Math.max(grandTotal - amountPaid, 0);
        const commissionAmount = grandTotal * (commissionPercent / 100);

        document.getElementById('order_subtotal')?.setAttribute('value', subtotal.toFixed(2));
        document.getElementById('order_discount_amount')?.setAttribute('value', discountAmount.toFixed(2));
        document.getElementById('order_vat_amount')?.setAttribute('value', vatAmount.toFixed(2));
        document.getElementById('order_balance_due')?.setAttribute('value', balanceDue.toFixed(2));
        document.getElementById('order_commission_amount')?.setAttribute('value', commissionAmount.toFixed(2));

        const grandTotalEl = document.getElementById('order_grand_total');
        if (grandTotalEl) {
            grandTotalEl.textContent = `TZS ${grandTotal.toFixed(2)}`;
        }
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
        const form = document.getElementById('orderForm');
        if (!form) return;

        // Collect all form data
        const formData = new FormData(form);
        
        // Remove empty approved_by field to ensure it sends as null or not sent at all
        if (!formData.get('approved_by')) {
            formData.delete('approved_by');
        }

        // Collect order items
        const items = [];
        document.querySelectorAll('#orderItemsBody .order-item-row').forEach((row, index) => {
            const product = row.querySelector('.order-product')?.value;
            const qty = row.querySelector('.order-qty')?.value;
            const unitPrice = row.querySelector('.order-unit-price')?.value;
            const discount = row.querySelector('.order-discount')?.value;
            const description = row.querySelector('.order-description')?.value;
            const sku = row.querySelector('.order-sku')?.value;
            const uom = row.querySelector('.order-uom')?.value;
            const lineTotal = row.querySelector('.order-total')?.value;

            if (product && qty) {
                items.push({
                    product_id: product,
                    quantity: parseFloat(qty),
                    unit_price: parseFloat(unitPrice || 0),
                    discount_percent: parseFloat(discount || 0),
                    description: description || '',
                    sku: sku || '',
                    unit_of_measure: uom || 'Piece',
                    line_total: parseFloat(lineTotal || 0),
                });
            }
        });

        if (items.length === 0) {
            window.showAlert('error', 'Please add at least one item to the order.');
            return false;
        }

        // Add items to form data
        items.forEach((item, index) => {
            Object.entries(item).forEach(([key, value]) => {
                formData.append(`items[${index}][${key}]`, value);
            });
        });

        // Show loader
        const loader = document.getElementById('customerCreateLoader');
        const messageEl = document.getElementById('customerCreateLoaderText');
        if (loader) {
            if (messageEl) messageEl.textContent = 'Creating order...';
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        // Submit via fetch
        fetch('{{ route("orders.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to create order');
                });
            }
            return response.json();
        })
        .then(data => {
            if (loader) loader.classList.add('hidden');
            window.showAlert('success', data.message || 'Order created successfully!');
            resetOrderForm();
            closeLocalModal('modalAddOrder');
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error('Error creating order:', error);
            if (loader) loader.classList.add('hidden');
            window.showAlert('error', error.message || 'Failed to create order. Please try again.');
        });

        closeLocalModal('modalAddOrder');
        return false;
    }

    function submitAddContract(e) {
        e.preventDefault();
        window.showAlert('info', 'Contract form submitted');
        closeLocalModal('modalAddContract');
        return false;
    }

    function toggleOrderDetails(orderId) {
        const detailsRow = document.getElementById(`order-details-${orderId}`);
        if (!detailsRow) return;

        const shouldOpen = detailsRow.classList.contains('hidden');

        document.querySelectorAll('[id^="order-details-"]').forEach(row => {
            row.classList.add('hidden');
        });

        if (shouldOpen) {
            detailsRow.classList.remove('hidden');
        }
    }

    function getProductSelectHTML(selectedProductId = '') {
        const container = document.getElementById('productOptionsContainer');
        if (!container) {
            return '<option value="">-- Select Product --</option>';
        }
        
        let html = '<option value="">-- Select Product --</option>';
        container.querySelectorAll('.product-option').forEach(el => {
            const id = el.getAttribute('data-id');
            const name = el.getAttribute('data-name');
            const selected = selectedProductId == id ? 'selected' : '';
            html += `<option value="${id}" ${selected}>${name}</option>`;
        });
        return html;
    }

    function getCustomerSelectHTML(selectedCustomerId = '') {
        const container = document.getElementById('customerOptionsContainer');
        if (!container) {
            return '<option value="">-- Select Customer --</option>';
        }
        
        let html = '<option value="">-- Select Customer --</option>';
        container.querySelectorAll('.customer-option').forEach(el => {
            const id = el.getAttribute('data-id');
            const name = el.getAttribute('data-name');
            const selected = selectedCustomerId == id ? 'selected' : '';
            html += `<option value="${id}" ${selected}>${name}</option>`;
        });
        return html;
    }


    function editOrder(orderId) {
        const loader = document.getElementById('orderLoader');
        const messageEl = document.getElementById('orderLoaderText');

        if (loader) {
            if (messageEl) messageEl.textContent = 'Loading order details...';
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        fetch(`{{ url('orders') }}/${orderId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to load order');
            return response.json();
        })
        .then(order => {
            if (loader) loader.classList.add('hidden');
            
            // Store current order ID for update
            window.editingOrderId = order.id;
            
            // Populate form fields
            document.getElementById('edit_order_no').value = order.order_number || '';
            document.getElementById('edit_order_date').value = order.order_date || '';
            document.getElementById('edit_expected_delivery_date').value = order.expected_delivery_date || '';
            document.getElementById('edit_order_priority').value = order.priority || 'Normal';
            document.getElementById('edit_order_company').value = order.company_id || '';
            document.getElementById('edit_order_department').value = order.department_id || '';
            document.getElementById('edit_order_type').value = order.order_type || 'Sale';
            document.getElementById('edit_order_status').value = order.status || 'Draft';
            
            // Populate customer dropdown with HTML options
            const customerSelect = document.getElementById('edit_order_customer');
            if (customerSelect) {
                customerSelect.innerHTML = getCustomerSelectHTML(order.customer_id);
            }
            
            document.getElementById('edit_order_billing_address').value = order.billing_address || '';
            document.getElementById('edit_order_shipping_address').value = order.shipping_address || '';
            document.getElementById('edit_order_payment_status').value = order.payment_status || 'Unpaid';
            document.getElementById('edit_order_payment_method').value = order.payment_method || '';
            document.getElementById('edit_order_payment_date').value = order.payment_date || '';
            document.getElementById('edit_order_amount_paid').value = order.amount_paid || 0;
            document.getElementById('edit_order_payment_reference').value = order.payment_reference || '';
            document.getElementById('edit_order_credit_terms').value = order.credit_terms || 30;
            document.getElementById('edit_order_delivery_method').value = order.delivery_method || '';
            document.getElementById('edit_order_delivery_date').value = order.delivery_date || '';
            document.getElementById('edit_order_delivery_status').value = order.delivery_status || 'Not Dispatched';
            document.getElementById('edit_order_driver_name').value = order.driver_name || '';
            document.getElementById('edit_order_vehicle_plate_number').value = order.vehicle_plate_number || '';
            document.getElementById('edit_order_delivery_notes').value = order.delivery_notes || '';
            document.getElementById('edit_order_sales_rep').value = order.sales_rep_id || '';
            document.getElementById('edit_order_approved_by').value = order.approved_by || '';
            
            const commissionPercent = document.getElementById('edit_order_commission_percent');
            if (commissionPercent) commissionPercent.value = order.commission_percent || 0;
            
            const commissionAmount = document.getElementById('edit_order_commission_amount');
            if (commissionAmount) commissionAmount.value = order.commission_amount || 0;
            
            document.getElementById('edit_order_internal_notes').value = order.internal_notes || '';
            document.getElementById('edit_order_customer_notes').value = order.customer_notes || '';
            document.getElementById('edit_order_terms_and_conditions').value = order.terms_and_conditions || '';
            document.getElementById('edit_order_subtotal').value = order.subtotal || 0;
            document.getElementById('edit_order_discount_percent').value = order.discount_percent || 0;
            document.getElementById('edit_order_discount_amount').value = order.discount_amount || 0;
            document.getElementById('edit_order_vat_percent').value = order.vat_percent || 18;
            document.getElementById('edit_order_vat_amount').value = order.vat_amount || 0;
            document.getElementById('edit_order_shipping_cost').value = order.shipping_cost || 0;
            document.getElementById('edit_order_other_charges').value = order.other_charges || 0;
            document.getElementById('edit_order_grand_total').value = order.grand_total || 0;
            document.getElementById('edit_order_balance_due').value = order.balance_due || 0;

            // Populate order items
            const itemsBody = document.getElementById('editOrderItemsBody');
            if (itemsBody && order.items && order.items.length > 0) {
                itemsBody.innerHTML = '';
                order.items.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = 'order-item-row';
                    
                    // Build product select HTML
                    const productSelectHtml = getProductSelectHTML(item.product_id);
                    
                    row.innerHTML = `
                        <td class="px-3 py-2 text-sm text-slate-600 item-number">${index + 1}</td>
                        <td class="px-3 py-2">
                            <select class="order-product block w-full border border-slate-200 rounded p-2 text-sm" onchange="handleOrderProductChange(this)">
                                ${productSelectHtml}
                            </select>
                        </td>
                        <td class="px-3 py-2">
                            <input type="text" class="order-sku block w-full border border-slate-200 rounded p-2 text-sm bg-slate-50" readonly />
                        </td>
                        <td class="px-3 py-2">
                            <input type="text" class="order-description block w-full border border-slate-200 rounded p-2 text-sm" value="${(item.description || '').replace(/"/g, '&quot;')}" />
                        </td>
                        <td class="px-3 py-2">
                            <input type="number" min="1" value="${item.quantity || 1}" class="order-qty block w-full border border-slate-200 rounded p-2 text-sm" oninput="recalculateOrderTotals()" />
                        </td>
                        <td class="px-3 py-2">
                            <select class="order-uom block w-full border border-slate-200 rounded p-2 text-sm">
                                <option value="Piece" ${item.unit_of_measure === 'Piece' ? 'selected' : ''}>Piece</option>
                                <option value="Box" ${item.unit_of_measure === 'Box' ? 'selected' : ''}>Box</option>
                                <option value="Kg" ${item.unit_of_measure === 'Kg' ? 'selected' : ''}>Kg</option>
                                <option value="Litre" ${item.unit_of_measure === 'Litre' ? 'selected' : ''}>Litre</option>
                            </select>
                        </td>
                        <td class="px-3 py-2">
                            <input type="number" min="0" value="${item.unit_price || 0}" class="order-unit-price block w-full border border-slate-200 rounded p-2 text-sm" oninput="recalculateOrderTotals()" />
                        </td>
                        <td class="px-3 py-2">
                            <input type="number" min="0" value="${item.discount_percent || 0}" class="order-discount block w-full border border-slate-200 rounded p-2 text-sm" oninput="recalculateOrderTotals()" />
                        </td>
                        <td class="px-3 py-2">
                            <input type="number" min="0" value="${item.line_total || 0}" class="order-total block w-full border border-slate-200 rounded p-2 text-sm bg-slate-50" readonly />
                        </td>
                        <td class="px-3 py-2 text-center">
                            <button type="button" onclick="removeOrderItemRow(this)" class="text-red-600 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </td>
                    `;
                    itemsBody.appendChild(row);
                    // Set product select value
                    row.querySelector('.order-product').value = item.product_id || '';
                });
            }

            // Update form action to PUT route
            const form = document.getElementById('editOrderForm');
            if (form) {
                form.action = `{{ url('orders') }}/${order.id}`;
            }

            // Show modal
            openLocalModal('modalEditOrder');
        })
        .catch(error => {
            console.error('Error loading order:', error);
            if (loader) loader.classList.add('hidden');
            if (window.showAlert) {
                window.showAlert('error', 'Failed to load order details. Please try again.');
            }
        });
    }


    function resetEditOrderForm() {
        const form = document.getElementById('editOrderForm');
        if (form) {
            form.reset();
        }
        window.editingOrderId = null;
    }

    function submitEditOrder(e) {
        e.preventDefault();
        
        if (!window.editingOrderId) {
            if (window.showAlert) {
                window.showAlert('error', 'Order ID not found');
            }
            return false;
        }

        const form = document.getElementById('editOrderForm');
        if (!form) return false;

        // Collect all form data
        const formData = new FormData(form);

        // Remove empty approved_by field
        if (!formData.get('approved_by')) {
            formData.delete('approved_by');
        }

        // Collect order items
        const items = [];
        document.querySelectorAll('#editOrderItemsBody .order-item-row').forEach((row, index) => {
            const product = row.querySelector('.order-product')?.value;
            const qty = row.querySelector('.order-qty')?.value;
            const unitPrice = row.querySelector('.order-unit-price')?.value;
            const discount = row.querySelector('.order-discount')?.value;
            const description = row.querySelector('.order-description')?.value;
            const sku = row.querySelector('.order-sku')?.value;
            const uom = row.querySelector('.order-uom')?.value;
            const lineTotal = row.querySelector('.order-total')?.value;

            if (product && qty) {
                items.push({
                    product_id: product,
                    quantity: parseFloat(qty),
                    unit_price: parseFloat(unitPrice || 0),
                    discount_percent: parseFloat(discount || 0),
                    description: description || '',
                    sku: sku || '',
                    unit_of_measure: uom || 'Piece',
                    line_total: parseFloat(lineTotal || 0),
                });
            }
        });

        if (items.length === 0) {
            if (window.showAlert) {
                window.showAlert('error', 'Please add at least one item to the order.');
            }
            return false;
        }

        // Add items to form data
        items.forEach((item, index) => {
            Object.entries(item).forEach(([key, value]) => {
                formData.append(`items[${index}][${key}]`, value);
            });
        });

        // Show loader
        const loader = document.getElementById('orderLoader');
        const messageEl = document.getElementById('orderLoaderText');
        if (loader) {
            if (messageEl) messageEl.textContent = 'Updating order...';
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        // Submit via fetch
        fetch(`{{ url('orders') }}/${window.editingOrderId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to update order');
                });
            }
            return response.json();
        })
        .then(data => {
            if (loader) loader.classList.add('hidden');
            if (window.showAlert) {
                window.showAlert('success', data.message || 'Order updated successfully!');
            }
            resetEditOrderForm();
            closeLocalModal('modalEditOrder');
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error('Error updating order:', error);
            if (loader) loader.classList.add('hidden');
            if (window.showAlert) {
                window.showAlert('error', error.message || 'Failed to update order. Please try again.');
            }
        });

        return false;
    }


    function confirmDeleteOrder(orderId, orderNumber) {
        if (typeof openConfirm !== 'function') {
            if (window.showAlert) {
                window.showAlert('info', `Delete order ${orderNumber} is not wired yet.`);
            }
            return;
        }

        openConfirm({
            title: 'Delete Order',
            message: `Are you sure you want to delete order "${orderNumber}"? This action cannot be undone.`,
            confirmText: 'Delete',
            cancelText: 'Cancel',
            variant: 'danger',
            onConfirm: function() {
                deleteOrder(orderId, orderNumber);
            }
        });
    }

    function deleteOrder(orderId, orderNumber) {
        const loader = document.getElementById('orderLoader');
        const messageEl = document.getElementById('orderLoaderText');

        if (loader) {
            if (messageEl) messageEl.textContent = 'Deleting order...';
            loader.classList.remove('hidden');
            loader.classList.add('flex');
        }

        fetch(`{{ url('orders') }}/${orderId}`, {
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
            throw new Error('Failed to delete order');
        })
        .then(() => {
            if (loader) loader.classList.add('hidden');
            if (window.showAlert) {
                window.showAlert('success', `Order ${orderNumber} deleted successfully!`);
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error('Error deleting order:', error);
            if (loader) loader.classList.add('hidden');
            if (window.showAlert) {
                window.showAlert('error', 'Failed to delete order. Please try again.');
            }
        });
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
