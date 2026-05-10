<script>

    function slugifySkuPart(value) {
        return (value || '')
            .toString()
            .trim()
            .toUpperCase()
            .replace(/[^A-Z0-9]+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    function generateSKU(categoryValue, productName) {
        const categoryPart = slugifySkuPart(categoryValue);
        const namePart = slugifySkuPart(productName);
        const suffix = Math.floor(100 + Math.random() * 900);

        if (!categoryPart || !namePart) {
            return '';
        }
        return `${categoryPart}-${namePart}-${suffix}`;
    }

    function generateOrderItemSku(productName) {
        return generateSKU('POITEM', productName);
    }

    function confirmDeletePurchaseOrder(poId, poNumber) {
        if (typeof openConfirm !== 'function') {
            alert('Confirmation dialog is not available right now.');
            return;
        }

        const deleteUrlTemplate = @js(route('purchase-orders.destroy', ['purchaseOrder' => '__ID__']));
        const deleteUrl = deleteUrlTemplate.replace('__ID__', poId);

        openConfirm({
            title: 'Delete Purchase Order',
            message: `Are you sure you want to delete purchase order "${poNumber}"? This action cannot be undone.`,
            confirmText: 'Delete',
            cancelText: 'Cancel',
            variant: 'warning',
            onConfirm: function () {
                const form = document.getElementById(`delete-po-form-${poId}`);
                if (!form) return;
                form.action = deleteUrl;
                form.submit();
            }
        });
    }

    function deleteSupplier(supplierId, supplierName) {
        if (typeof openConfirm !== 'function') {
            alert('Confirmation dialog is not available right now.');
            return;
        }

        const deleteUrlTemplate = @js(route('suppliers.destroy', ['supplier' => '__ID__']));
        const deleteUrl = deleteUrlTemplate.replace('__ID__', supplierId);

        openConfirm({
            title: 'Delete Supplier',
            message: `Are you sure you want to delete "${supplierName}"? This action cannot be undone.`,
            confirmText: 'Delete',
            cancelText: 'Cancel',
            variant: 'danger',
            onConfirm: async function () {
                try {
                    const loader = document.getElementById('supplierDeleteLoading');
                    if (loader) loader.classList.remove('hidden');

                    const response = await fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        let errorMessage = 'Failed to delete supplier';
                        try {
                            const error = await response.json();
                            errorMessage = error.message || errorMessage;
                        } catch (parseError) {
                            const fallbackText = await response.text();
                            if (fallbackText) {
                                errorMessage = fallbackText;
                            }
                        }
                        if (loader) loader.classList.add('hidden');
                        alert('Error: ' + errorMessage);
                        return;
                    }

                    location.reload();
                } catch (err) {
                    const loader = document.getElementById('supplierDeleteLoading');
                    if (loader) loader.classList.add('hidden');
                    console.error('Delete error:', err);
                    alert('Error deleting supplier: ' + err.message);
                }
            }
        });
    }

    function generateBarcode() {
        return `BAR-${Date.now().toString().slice(-8)}`;
    }

    function initProductModal() {
        if (window.__inventoryProductModalInitialized) return;
        window.__inventoryProductModalInitialized = true;

        const skuToggle = document.getElementById('skuAuto');
        const barcodeToggle = document.getElementById('barAuto');
        const skuInput = document.querySelector('input[name="sku"]');
        const barcodeInput = document.querySelector('input[name="barcode"]');
        const categorySelect = document.querySelector('select[name="category"]');
        const productNameInput = document.querySelector('input[name="name"]');

        if (skuToggle && skuInput) {
            const syncSkuState = () => {
                skuInput.readOnly = skuToggle.checked;
                skuInput.classList.toggle('bg-slate-50', skuToggle.checked);
                skuInput.classList.toggle('text-slate-600', skuToggle.checked);

                if (!skuToggle.checked) return;

                const nextSku = generateSKU(categorySelect?.value, productNameInput?.value);
                skuInput.value = nextSku || '';
            };

            const syncSkuPreview = () => {
                if (!skuToggle.checked) return;
                const nextSku = generateSKU(categorySelect?.value, productNameInput?.value);
                skuInput.value = nextSku || '';
            };

            skuToggle.addEventListener('change', syncSkuState);
            categorySelect?.addEventListener('change', syncSkuPreview);
            productNameInput?.addEventListener('input', syncSkuPreview);
            syncSkuState();
        }

        if (barcodeToggle && barcodeInput) {
            const syncBarcodeState = () => {
                barcodeInput.readOnly = barcodeToggle.checked;
                barcodeInput.classList.toggle('bg-slate-50', barcodeToggle.checked);
                barcodeInput.classList.toggle('text-slate-600', barcodeToggle.checked);

                if (barcodeToggle.checked) {
                    barcodeInput.value = generateBarcode();
                }
            };

            barcodeToggle.addEventListener('change', syncBarcodeState);
            syncBarcodeState();
        }
    }
    
    function switchTab(tabName, buttonEl) {
        // Hide all tab panes
        const panes = document.querySelectorAll('.tab-content');
        panes.forEach(pane => pane.style.display = 'none');

        // Remove active class from all buttons
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('active', 'text-slate-800', 'border-b-2', 'border-brand-600', 'font-semibold');
            btn.classList.add('text-slate-500', 'hover:text-slate-700');
        });

        // Show selected pane and highlight button
        const selectedPane = document.getElementById(tabName + 'Pane');
        if (selectedPane) {
            selectedPane.style.display = 'block';
            // initialize product modal helpers
            if (typeof initProductModal === 'function') initProductModal();
        }

        if (buttonEl) {
            buttonEl.classList.add('active', 'text-slate-800', 'border-b-2', 'border-brand-600', 'font-semibold');
            buttonEl.classList.remove('text-slate-500', 'hover:text-slate-700');
        }
    }

    function openLocalModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');

        if (id === 'modalAddSupplier') {
            const supplierIdInput = document.getElementById('supplierId');
            if (supplierIdInput && !supplierIdInput.value) {
                const randomPart = Math.floor(1000 + Math.random() * 9000);
                supplierIdInput.value = `SUP-${randomPart}`;
            }
        }
    }

    function closeLocalModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
        // reset product form when closing the add/edit product modal
        if (id === 'modalAddProduct') {
            const form = document.getElementById('productForm');
            if (form && form.dataset.baseAction) {
                form.action = form.dataset.baseAction;
                const methodEl = document.getElementById('productFormMethod');
                if (methodEl) methodEl.value = 'POST';
                const pid = document.getElementById('product_id');
                if (pid) pid.value = '';
            }
            // re-enable submit button if it was disabled
            const submitBtn = document.getElementById('submitProductBtn');
            if (submitBtn) { submitBtn.disabled = false; submitBtn.classList.remove('opacity-50', 'cursor-not-allowed'); }
            const loader = document.getElementById('productSaveLoading');
            if (loader) loader.classList.add('hidden');
        }
        // reset purchase order form when closing the add/edit PO modal
        if (id === 'modalAddPO') {
            resetPOForm();
        }
    }

    function toggleProductDetails(productId) {
        const row = document.getElementById('details-' + productId);
        if (!row) return;
        row.classList.toggle('hidden');
    }

    function toggleSupplierDetails(supplierId) {
        const row = document.getElementById('details-' + supplierId);
        if (!row) return;
        row.classList.toggle('hidden');
    }

    function togglePODetails(poId) {
        const row = document.getElementById('details-' + poId);
        if (!row) return;
        row.classList.toggle('hidden');
    }

    function submitAddProduct(e) {
        e?.preventDefault();
        closeLocalModal('modalAddProduct');
        alert('Product added (demo)');
    }

    function resetSupplierForm() {
        const form = document.getElementById('supplierForm');
        if (form) {
            form.reset();
            const methodInput = document.getElementById('supplierMethod');
            if (methodInput) methodInput.value = 'POST';
            const supplierIdInput = document.getElementById('supplierId');
            if (supplierIdInput) {
                supplierIdInput.value = '';
                supplierIdInput.readOnly = false;
            }
            document.getElementById('modalAddSupplier').querySelector('h3').textContent = 'Add Supplier';
            document.getElementById('submitSupplierBtn').textContent = 'Save';
            document.getElementById('businessRegPreview').textContent = 'No file selected';
            document.getElementById('tinCertPreview').textContent = 'No file selected';
        }
    }

    function editSupplier(supplierData) {
        const form = document.getElementById('supplierForm');
        const methodInput = document.getElementById('supplierMethod');
        if (!form) return;

        // Set form to PUT method for update
        if (methodInput) methodInput.value = 'PUT';
        form.action = '{{ route('suppliers.update', ['supplier' => '__ID__']) }}'.replace('__ID__', supplierData.id);

        // Update modal title and button text
        document.getElementById('modalAddSupplier').querySelector('h3').textContent = 'Edit Supplier';
        document.getElementById('submitSupplierBtn').textContent = 'Update';

        // Populate form fields
        document.getElementById('supplierId').value = supplierData.supplier_id;
        document.getElementById('supplierId').readOnly = true;
        document.querySelector('input[name="supplier_name"]').value = supplierData.supplier_name || '';
        document.querySelector('select[name="supplier_type"]').value = supplierData.supplier_type || '';
        document.querySelector('select[name="company_id"]').value = supplierData.company_id || '';
        document.querySelector('input[name="registration_number"]').value = supplierData.registration_number || '';
        document.querySelector('select[name="status"]').value = supplierData.status || 'Active';
        
        document.querySelector('input[name="contact_person_name"]').value = supplierData.contact_person_name || '';
        document.querySelector('input[name="phone_number"]').value = supplierData.phone_number || '';
        document.querySelector('input[name="alternative_phone_number"]').value = supplierData.alternative_phone_number || '';
        document.querySelector('input[name="email"]').value = supplierData.email || '';
        document.querySelector('input[name="website"]').value = supplierData.website || '';
        
        document.querySelector('input[name="country"]').value = supplierData.country || 'Tanzania';
        document.querySelector('select[name="region"]').value = supplierData.region || '';
        document.querySelector('input[name="district"]').value = supplierData.district || '';
        document.querySelector('input[name="po_box"]').value = supplierData.po_box || '';
        document.querySelector('textarea[name="street_address"]').value = supplierData.street_address || '';
        
        // Handle categories (checkboxes)
        const categories = (supplierData.categories_supplied || '').split(', ').filter(c => c);
        document.querySelectorAll('input[name="categories_supplied[]"]').forEach(checkbox => {
            checkbox.checked = categories.includes(checkbox.value);
        });
        
        document.querySelector('textarea[name="products_supplied"]').value = supplierData.products_supplied || '';
        document.querySelector('select[name="lead_time"]').value = supplierData.lead_time || '';
        document.querySelector('input[name="minimum_order_value"]').value = supplierData.minimum_order_value || '';
        document.querySelector('select[name="payment_terms"]').value = supplierData.payment_terms || '';
        
        document.querySelector('select[name="bank_name"]').value = supplierData.bank_name || '';
        document.querySelector('input[name="account_name"]').value = supplierData.account_name || '';
        document.querySelector('input[name="account_number"]').value = supplierData.account_number || '';
        document.querySelector('input[name="branch_name"]').value = supplierData.branch_name || '';
        document.querySelector('input[name="mobile_money_number"]').value = supplierData.mobile_money_number || '';
        document.querySelector('select[name="preferred_payment_method"]').value = supplierData.preferred_payment_method || '';
        
        document.querySelector('select[name="rating"]').value = supplierData.rating || '';
        document.querySelector('input[name="contract_start_date"]').value = supplierData.contract_start_date || '';
        document.querySelector('input[name="contract_end_date"]').value = supplierData.contract_end_date || '';
        document.querySelector('textarea[name="notes"]').value = supplierData.notes || '';

        // Open modal
        openLocalModal('modalAddSupplier');
    }

    function submitAddSupplier(e) {
        const form = document.getElementById('supplierForm');
        const loader = document.getElementById('supplierSaveLoading');
        const submitBtn = document.getElementById('submitSupplierBtn');

        if (loader && submitBtn) {
            loader.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    function updateSupplierFilePreview(inputEl, previewId) {
        const preview = document.getElementById(previewId);
        if (!preview) return;

        if (!inputEl || !inputEl.files || !inputEl.files.length) {
            preview.className = 'mt-2 text-xs text-slate-500';
            preview.textContent = 'No file selected';
            return;
        }

        const file = inputEl.files[0];
        const isImage = file.type.startsWith('image/');

        if (isImage) {
            const reader = new FileReader();
            reader.onload = function (event) {
                preview.className = 'mt-2';
                preview.innerHTML = `
                    <p class="text-xs text-slate-600 mb-2">${file.name}</p>
                    <img src="${event.target.result}" alt="Preview" class="h-20 w-auto rounded border border-slate-300" />
                `;
            };
            reader.readAsDataURL(file);
            return;
        }

        preview.className = 'mt-2 text-xs text-slate-600';
        preview.textContent = file.name;
    }

    function submitAddPO(e) {
        e?.preventDefault();
        closeLocalModal('modalAddPO');
        alert('Purchase order added (demo)');
    }

    function openEditProduct(id) {
        const row = document.querySelector(`tr[data-product-id="${id}"]`);
        if (!row) return;

        const form = document.getElementById('productForm');
        if (!form) return;

        // store base action if not already stored
        if (!form.dataset.baseAction) form.dataset.baseAction = form.action || '/products';

        form.action = `/products/${id}`;
        document.getElementById('productFormMethod').value = 'PUT';
        document.getElementById('product_id').value = id;

        // populate fields from data attributes
        const set = (selector, value) => {
            const el = document.getElementById(selector);
            if (!el) return;
            el.value = value ?? '';
            // trigger change for select dependent handlers
            el.dispatchEvent(new Event('change', { bubbles: true }));
        };

        set('product_name', row.dataset.productName);
        set('product_sku', row.dataset.productSku);
        set('product_barcode', row.dataset.productBarcode);
        set('product_category', row.dataset.productCategory);
        set('product_brand', row.dataset.productBrand);
        set('product_company', row.dataset.productCompanyId);
        set('product_status', row.dataset.productStatus);
        set('product_stock', row.dataset.productStock);
        set('product_unit_of_measure', row.dataset.productUnit);
        set('product_reorder_level', row.dataset.productReorder);
        set('product_location', row.dataset.productLocation);
        set('product_last_restocked_date', row.dataset.productLastRestocked);
        set('product_last_stock_movement', row.dataset.productLastMovement);
        set('product_cost_per_unit', row.dataset.productCost);
        set('product_selling_price', row.dataset.productSelling);
        set('product_profit_margin', row.dataset.productProfit);
        set('product_tax_vat', row.dataset.productTax);
        set('product_tax_vat_custom', '');
        set('product_supplier_id', row.dataset.productSupplier);
        set('product_expiry_date', row.dataset.productExpiry);
        set('product_product_description', row.dataset.productDescription);

        // turn off auto-generate toggles so the SKU/barcode show as-is
        const skuToggle = document.getElementById('skuAuto');
        const barToggle = document.getElementById('barAuto');
        if (skuToggle) { skuToggle.checked = false; skuToggle.dispatchEvent(new Event('change')); }
        if (barToggle) { barToggle.checked = false; barToggle.dispatchEvent(new Event('change')); }

        openLocalModal('modalAddProduct');
    }

    function confirmDeleteProduct(id, name) {
        const message = `Are you sure you want to delete product "${name}"? This action cannot be undone.`;
        if (typeof openConfirm !== 'function') {
            if (!confirm(message)) return;
            // fallback
        }

        openConfirm({
            title: 'Delete product',
            message,
            variant: 'danger',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            onConfirm: function () {
                const form = document.getElementById('productDeleteForm');
                if (!form) return;
                form.action = `/products/${id}`;

                const loader = document.getElementById('productDeleteLoading');
                if (loader) loader.classList.remove('hidden');

                form.submit();
            }
        });
    }

    // Initialize - show products tab by default
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('products', document.querySelector('[onclick*="switchTab(\'products\'"]'));

        const supplierForm = document.getElementById('supplierForm');
        if (supplierForm) {
            supplierForm.addEventListener('submit', function () {
                const loader = document.getElementById('supplierSaveLoading');
                const submitBtn = document.getElementById('submitSupplierBtn');
                if (loader && submitBtn) {
                    loader.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        }

        // Wire up product form submission to show loader
        const productForm = document.querySelector('#modalAddProduct form');
        if (productForm) {
            // preserve base action for create
            if (!productForm.dataset.baseAction) productForm.dataset.baseAction = productForm.action || '/products';

            productForm.addEventListener('submit', function() {
                const loader = document.getElementById('productSaveLoading');
                const submitBtn = document.getElementById('submitProductBtn');
                if (loader && submitBtn) {
                    loader.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        }

        // Set default PO date to today when modal opens
        const poModal = document.getElementById('modalAddPO');
        if (poModal) {
            const observer = new MutationObserver(() => {
                if (!poModal.classList.contains('hidden')) {
                    const poDateInput = document.querySelector('input[name="po_date"]');
                    if (poDateInput && !poDateInput.value) {
                        poDateInput.value = new Date().toISOString().split('T')[0];
                    }
                }
            });
            observer.observe(poModal, { attributes: true, attributeFilter: ['class'] });
        }
    });

    // ===== PURCHASE ORDER FUNCTIONS =====

    let poItemCount = 0;

    function addOrderItem() {
        poItemCount++;
        const tbody = document.getElementById('orderItemsBody');
        const row = document.createElement('tr');
        row.id = `poItem_${poItemCount}`;
        row.className = 'hover:bg-slate-50';
        row.innerHTML = `
            <td class="px-2 py-2">${poItemCount}</td>
            <td class="px-2 py-2"><input type="text" name="items[${poItemCount}][product_name]" placeholder="Product" class="w-full px-2 py-1 border border-slate-200 rounded text-xs" required></td>
            <td class="px-2 py-2"><input type="text" name="items[${poItemCount}][sku]" placeholder="Auto SKU" readonly class="w-full px-2 py-1 border border-slate-200 rounded text-xs bg-slate-50 text-slate-600"></td>
            <td class="px-2 py-2"><input type="number" name="items[${poItemCount}][quantity_ordered]" placeholder="0" min="1" class="w-full px-2 py-1 border border-slate-200 rounded text-xs text-right" onchange="calculateTotals()" required></td>
            <td class="px-2 py-2">
                <select name="items[${poItemCount}][unit_of_measure]" class="w-full px-2 py-1 border border-slate-200 rounded text-xs">
                    <option value="">Select Unit</option>
                    <option value="pieces">Pieces</option>
                    <option value="kg">Kilogram (kg)</option>
                    <option value="liters">Liters</option>
                    <option value="meters">Meters (m)</option>
                    <option value="bags">Bags</option>
                    <option value="boxes">Boxes</option>
                    <option value="drums">Drums</option>
                </select>
            </td>
            <td class="px-2 py-2"><input type="number" name="items[${poItemCount}][unit_price]" placeholder="0.00" step="0.01" class="w-full px-2 py-1 border border-slate-200 rounded text-xs text-right" onchange="calculateTotals()" required></td>
            <td class="px-2 py-2"><input type="text" name="items[${poItemCount}][total_price]" placeholder="0.00" class="w-full px-2 py-1 border border-slate-200 rounded text-xs text-right" readonly></td>
            <td class="px-2 py-2 text-center"><button type="button" onclick="removeOrderItem('poItem_${poItemCount}')" class="text-red-600 hover:text-red-900 text-xs">Remove</button></td>
        `;
        tbody.appendChild(row);

        const productNameInput = row.querySelector('input[name*="[product_name]"]');
        const skuInput = row.querySelector('input[name*="[sku]"]');
        const syncOrderItemSku = () => {
            if (!skuInput) return;
            skuInput.value = generateOrderItemSku(productNameInput?.value) || '';
        };

        productNameInput?.addEventListener('input', syncOrderItemSku);
        syncOrderItemSku();

        calculateTotals();
    }

    function removeOrderItem(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
            calculateTotals();
        }
    }

    function updateSupplierInfo() {
        const select = document.getElementById('supplierSelect');
        const option = select.options[select.selectedIndex];
        const contact = option.dataset.contact || '';
        const phone = option.dataset.phone || '';
        
        document.getElementById('supplierContact').value = contact;
        document.getElementById('supplierPhone').value = phone;
    }

    function calculateTotals() {
        const tbody = document.getElementById('orderItemsBody');
        const rows = tbody.querySelectorAll('tr');
        let subtotal = 0;

        rows.forEach(row => {
            const qtyInput = row.querySelector('input[name*="[quantity_ordered]"]');
            const priceInput = row.querySelector('input[name*="[unit_price]"]');
            const totalInput = row.querySelector('input[name*="[total_price]"]');

            if (qtyInput && priceInput && totalInput) {
                const qty = parseFloat(qtyInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = qty * price;
                totalInput.value = total.toFixed(2);
                subtotal += total;
            }
        });

        const discountPercent = parseFloat(document.querySelector('input[name="discount_percent"]').value) || 0;
        const vatPercent = parseFloat(document.querySelector('input[name="vat_percent"]').value) || 0;
        const shippingCost = parseFloat(document.querySelector('input[name="shipping_cost"]').value) || 0;
        const depositAmount = parseFloat(document.querySelector('input[name="deposit_amount"]').value) || 0;

        const discountAmount = subtotal * (discountPercent / 100);
        const vatAmount = (subtotal - discountAmount) * (vatPercent / 100);
        const totalAmount = subtotal - discountAmount + vatAmount + shippingCost;
        const balanceDue = totalAmount - depositAmount;

        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('discountAmount').value = discountAmount.toFixed(2);
        document.getElementById('vatAmount').value = vatAmount.toFixed(2);
        document.getElementById('totalAmount').value = totalAmount.toFixed(2);
        document.getElementById('balanceDue').value = balanceDue.toFixed(2);
    }

    function updateDocumentPreview(event) {
        const file = event.target.files[0];
        const previewDiv = document.getElementById('documentPreview');
        
        if (file) {
            previewDiv.innerHTML = `<span class="text-slate-700">📄 ${file.name}</span>`;
        } else {
            previewDiv.innerHTML = '<span class="text-slate-500">No file selected</span>';
        }
    }

    function resetPOForm() {
        const form = document.getElementById('poForm');
        if (form) {
            form.reset();
            poItemCount = 0;
            document.getElementById('orderItemsBody').innerHTML = '';
            document.getElementById('poNumber').value = '';
            document.getElementById('documentPreview').innerHTML = '<span class="text-slate-500">No file selected</span>';
            calculateTotals();
        }
    }

    function togglePODetails(poId) {
        const row = document.getElementById('details-' + poId);
        if (!row) return;
        row.classList.toggle('hidden');
    }

    function editPurchaseOrder(poId) {
        if (!poId) {
            console.error('No PO ID provided');
            return;
        }

        // Show loading state
        const form = document.getElementById('poForm');
        const modal = document.getElementById('modalAddPO');
        if (!modal) {
            console.error('Modal not found');
            return;
        }

        // Open modal in loading state
        openLocalModal('modalAddPO');

        // Fetch PO data from server
        fetch(`/purchase-orders/${poId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to load PO');
            return response.json();
        })
        .then(data => {
            const purchaseOrder = data.data || data;
            
            // Reset form first
            resetPOForm();

            // Get modal elements
            const modalTitle = modal.querySelector('h3');
            const submitBtn = form.querySelector('button[type="submit"]');

            // Update modal header
            modalTitle.textContent = 'Edit Purchase Order';
            submitBtn.textContent = 'Update Purchase Order';

            // Set form action to update route
            form.action = `/purchase-orders/${purchaseOrder.id}`;
            
            // Remove existing _method field if present
            let methodField = form.querySelector('input[name="_method"]');
            if (methodField) {
                methodField.remove();
            }
            
            // Add _method field for PUT request
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            // Populate Section 1: PO Identity
            document.querySelector('input[name="po_number"]').value = purchaseOrder.po_number || '';
            document.querySelector('input[name="po_date"]').value = purchaseOrder.po_date?.split('T')[0] || '';
            document.querySelector('input[name="expected_delivery_date"]').value = purchaseOrder.expected_delivery_date?.split('T')[0] || '';
            document.querySelector('select[name="company_id"]').value = purchaseOrder.company_id || '';
            document.querySelector('select[name="department_id"]').value = purchaseOrder.department_id || '';
            document.querySelector('select[name="priority_level"]').value = purchaseOrder.priority_level || 'Normal';
            document.querySelector('select[name="status"]').value = purchaseOrder.status || 'Draft';

            // Populate Section 2: Supplier Information
            const supplierSelect = document.querySelector('select[name="supplier_id"]');
            supplierSelect.value = purchaseOrder.supplier_id || '';
            updateSupplierInfo();
            document.querySelector('textarea[name="delivery_address"]').value = purchaseOrder.delivery_address || '';
            document.querySelector('select[name="delivery_method"]').value = purchaseOrder.delivery_method || '';

            // Populate Section 3: Order Items
            const itemsBody = document.getElementById('orderItemsBody');
            itemsBody.innerHTML = '';
            poItemCount = 0;

            if (purchaseOrder.items && purchaseOrder.items.length > 0) {
                purchaseOrder.items.forEach((item) => {
                    poItemCount++;
                    const row = document.createElement('tr');
                    row.id = `poItem_${poItemCount}`;
                    row.className = 'hover:bg-slate-50';
                    row.innerHTML = `
                        <td class="px-2 py-2">${poItemCount}</td>
                        <td class="px-2 py-2"><input type="text" name="items[${poItemCount}][product_name]" placeholder="Product" class="w-full px-2 py-1 border border-slate-200 rounded text-xs" required></td>
                        <td class="px-2 py-2"><input type="text" name="items[${poItemCount}][sku]" placeholder="Auto SKU" readonly class="w-full px-2 py-1 border border-slate-200 rounded text-xs bg-slate-50 text-slate-600"></td>
                        <td class="px-2 py-2"><input type="number" name="items[${poItemCount}][quantity_ordered]" placeholder="0" min="1" class="w-full px-2 py-1 border border-slate-200 rounded text-xs text-right" onchange="calculateTotals()" required></td>
                        <td class="px-2 py-2">
                            <select name="items[${poItemCount}][unit_of_measure]" class="w-full px-2 py-1 border border-slate-200 rounded text-xs">
                                <option value="">Select Unit</option>
                                <option value="pieces">Pieces</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="liters">Liters</option>
                                <option value="meters">Meters (m)</option>
                                <option value="bags">Bags</option>
                                <option value="boxes">Boxes</option>
                                <option value="drums">Drums</option>
                            </select>
                        </td>
                        <td class="px-2 py-2"><input type="number" name="items[${poItemCount}][unit_price]" placeholder="0.00" step="0.01" class="w-full px-2 py-1 border border-slate-200 rounded text-xs text-right" onchange="calculateTotals()" required></td>
                        <td class="px-2 py-2"><input type="text" name="items[${poItemCount}][total_price]" placeholder="0.00" class="w-full px-2 py-1 border border-slate-200 rounded text-xs text-right" readonly></td>
                        <td class="px-2 py-2 text-center"><button type="button" onclick="removeOrderItem('poItem_${poItemCount}')" class="text-red-600 hover:text-red-900 text-xs">Remove</button></td>
                    `;
                    itemsBody.appendChild(row);

                    // Populate item data
                    row.querySelector(`input[name="items[${poItemCount}][product_name]"]`).value = item.product_name || '';
                    row.querySelector(`input[name="items[${poItemCount}][sku]"]`).value = item.sku || '';
                    row.querySelector(`input[name="items[${poItemCount}][quantity_ordered]"]`).value = item.quantity_ordered || '';
                    row.querySelector(`select[name="items[${poItemCount}][unit_of_measure]"]`).value = item.unit_of_measure || '';
                    row.querySelector(`input[name="items[${poItemCount}][unit_price]"]`).value = item.unit_price || '';
                    row.querySelector(`input[name="items[${poItemCount}][total_price]"]`).value = item.total_price || '';
                });
            }

            // Populate Section 5: Payment Information
            document.querySelector('select[name="payment_terms"]').value = purchaseOrder.payment_terms || '';
            document.querySelector('select[name="payment_method"]').value = purchaseOrder.payment_method || '';
            document.querySelector('input[name="discount_percent"]').value = purchaseOrder.discount_percent || '';
            document.querySelector('input[name="vat_percent"]').value = purchaseOrder.vat_percent || 18;
            document.querySelector('input[name="shipping_cost"]').value = purchaseOrder.shipping_cost || '';
            document.querySelector('input[name="deposit_amount"]').value = purchaseOrder.deposit_amount || '';

            // Populate Section 6: Approval & Authorization
            document.querySelector('select[name="requested_by"]').value = purchaseOrder.requested_by || '';
            document.querySelector('select[name="approved_by"]').value = purchaseOrder.approved_by || '';
            document.querySelector('input[name="approval_date"]').value = purchaseOrder.approval_date?.split('T')[0] || '';
            document.querySelector('textarea[name="authorization_notes"]').value = purchaseOrder.authorization_notes || '';

            // Populate Section 7: Attachments & Notes
            document.querySelector('textarea[name="internal_notes"]').value = purchaseOrder.internal_notes || '';
            document.querySelector('textarea[name="terms_and_conditions"]').value = purchaseOrder.terms_and_conditions || '';

            // Update document preview
            if (purchaseOrder.supporting_document_name) {
                document.getElementById('documentPreview').innerHTML = `<span class="text-slate-600"><strong>${purchaseOrder.supporting_document_name}</strong></span>`;
            }

            // Recalculate totals
            calculateTotals();
        })
        .catch(error => {
            console.error('Error loading PO:', error);
            alert('Error loading purchase order. Please try again.');
            modal.classList.add('hidden');
        });
    }

    function resetPOModalForCreate() {
        const form = document.getElementById('poForm');
        const modal = document.getElementById('modalAddPO');
        const modalTitle = modal.querySelector('h3');
        const submitBtn = form.querySelector('button[type="submit"]');

        // Reset modal header and button
        modalTitle.textContent = 'Add Purchase Order';
        submitBtn.textContent = 'Save Purchase Order';

        // Reset form action to create route
        form.action = '/purchase-orders';

        // Remove _method field if it exists (for PUT)
        let methodField = form.querySelector('input[name="_method"]');
        if (methodField) {
            methodField.remove();
        }

        // Reset the form
        resetPOForm();

        // Open the modal
        openLocalModal('modalAddPO');
    }
</script>
