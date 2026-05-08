<script>
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
    });
</script>
