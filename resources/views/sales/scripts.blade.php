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
        document.getElementById('customerForm')?.reset();
        openLocalModal('modalAddCustomer');
    }

    function openAddOrderModal() {
        document.getElementById('orderForm')?.reset();
        openLocalModal('modalAddOrder');
    }

    function openAddContractModal() {
        document.getElementById('contractForm')?.reset();
        openLocalModal('modalAddContract');
    }

    // Form submission handlers (placeholder for potential backend integration)
    function submitAddCustomer(e) {
        e.preventDefault();
        window.showAlert('info', 'Customer form submitted');
        closeLocalModal('modalAddCustomer');
        return false;
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

    // Initialize tab on page load
    document.addEventListener('DOMContentLoaded', () => { switchTab('customers', document.querySelector('.tab-btn')); });
</script>
