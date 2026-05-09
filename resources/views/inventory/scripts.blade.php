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
    }

    function closeLocalModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
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

    function submitAddPO(e) {
        e?.preventDefault();
        closeLocalModal('modalAddPO');
        alert('Purchase order added (demo)');
    }

    // Initialize - show products tab by default
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('products', document.querySelector('[onclick*="switchTab(\'products\'"]'));

        // Wire up product form submission to show loader
        const productForm = document.querySelector('#modalAddProduct form');
        if (productForm) {
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
