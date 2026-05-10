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

    function submitAddSupplier(e) {
        e?.preventDefault();
        closeLocalModal('modalAddSupplier');
        alert('Supplier added (demo)');
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
    });
</script>
