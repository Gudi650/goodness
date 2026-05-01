<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Inventory - Goodness Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: Inter, sans-serif
        }

        h1,
        h2,
        nav,
        button {
            font-family: Outfit, sans-serif
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-20 p-6 min-h-screen">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Inventory & Procurement</h1>
            <p class="text-sm text-slate-500">Products, suppliers and purchase orders</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <div class="flex gap-2">
                    <button id="tabProducts" class="px-3 py-2 bg-green-50 text-green-700 rounded-md">Products</button>
                    <button id="tabSuppliers" class="px-3 py-2 text-slate-600 rounded-md">Suppliers</button>
                    <button id="tabPO" class="px-3 py-2 text-slate-600 rounded-md">Purchase Orders</button>
                </div>
                <div id="actionButton"></div>
            </div>
            <div id="invContent"></div>
        </div>
    </main>

    <script>
        const products = [{
            id: 1,
            name: 'Widget A',
            sku: 'W-A',
            stock: 120
        }, {
            id: 2,
            name: 'Widget B',
            sku: 'W-B',
            stock: 45
        }];

        const suppliers = [];
        const purchaseOrders = [];

        function renderProducts() {
            return `<div class="overflow-x-auto"><table class="min-w-full"><thead class="bg-slate-50"><tr><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Product</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">SKU</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Stock</th></tr></thead><tbody class="divide-y divide-slate-100">${products.map(p=>`<tr><td class="px-4 py-3 text-sm">${p.name}</td><td class="px-4 py-3 text-sm">${p.sku}</td><td class="px-4 py-3 text-sm text-right">${p.stock}</td></tr>`).join('')}</tbody></table></div>`;
        }

        function renderSuppliers() {
            if (suppliers.length === 0) return '<p class="text-sm text-slate-500">No suppliers yet.</p>';
            return `<div class="overflow-x-auto"><table class="min-w-full"><thead class="bg-slate-50"><tr><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Supplier</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Contact</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Email</th></tr></thead><tbody class="divide-y divide-slate-100">${suppliers.map(s=>`<tr><td class="px-4 py-3 text-sm">${s.name}</td><td class="px-4 py-3 text-sm">${s.contact}</td><td class="px-4 py-3 text-sm">${s.email}</td></tr>`).join('')}</tbody></table></div>`;
        }

        function renderPurchaseOrders() {
            if (purchaseOrders.length === 0) return '<p class="text-sm text-slate-500">No purchase orders yet.</p>';
            return `<div class="overflow-x-auto"><table class="min-w-full"><thead class="bg-slate-50"><tr><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">PO Number</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Supplier</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-right">Amount</th></tr></thead><tbody class="divide-y divide-slate-100">${purchaseOrders.map(po=>`<tr><td class="px-4 py-3 text-sm">${po.poNumber}</td><td class="px-4 py-3 text-sm">${po.supplier}</td><td class="px-4 py-3 text-sm text-right">${po.amount.toLocaleString()}</td></tr>`).join('')}</tbody></table></div>`;
        }

        function renderButton(label, onclick) {
            return `<button onclick="${onclick}" class="px-4 py-2 bg-brand-600 text-white rounded-md hover:bg-brand-700">${label}</button>`;
        }

        function openAddProductModal() {
            const body = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Product Name</label>
                        <input type="text" id="productName" placeholder="Widget C" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">SKU</label>
                        <input type="text" id="productSku" placeholder="W-C" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Stock Quantity</label>
                        <input type="number" id="productStock" placeholder="0" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                </div>
            `;
            window.openModal('Add Product', body, () => {
                const name = document.getElementById('productName').value.trim();
                const sku = document.getElementById('productSku').value.trim();
                const stock = parseInt(document.getElementById('productStock').value) || 0;

                if (!name) { window.showAlert('error', 'Product name is required'); return false; }
                if (!sku) { window.showAlert('error', 'SKU is required'); return false; }
                if (stock < 0) { window.showAlert('error', 'Stock must be 0 or greater'); return false; }

                products.push({ id: Date.now(), name, sku, stock });
                setActiveTab('tabProducts');
                window.closeModal();
                window.showAlert('success', 'Product added successfully');
                return true;
            });
        }

        function openAddSupplierModal() {
            const body = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Supplier Name</label>
                        <input type="text" id="supplierName" placeholder="Supplier Inc" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Contact Person</label>
                        <input type="text" id="supplierContact" placeholder="John Doe" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" id="supplierEmail" placeholder="contact@supplier.com" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                </div>
            `;
            window.openModal('Add Supplier', body, () => {
                const name = document.getElementById('supplierName').value.trim();
                const contact = document.getElementById('supplierContact').value.trim();
                const email = document.getElementById('supplierEmail').value.trim();

                if (!name) { window.showAlert('error', 'Supplier name is required'); return false; }
                if (!contact) { window.showAlert('error', 'Contact person is required'); return false; }
                if (!email) { window.showAlert('error', 'Email is required'); return false; }

                suppliers.push({ id: Date.now(), name, contact, email });
                setActiveTab('tabSuppliers');
                window.closeModal();
                window.showAlert('success', 'Supplier added successfully');
                return true;
            });
        }

        function openAddPOModal() {
            const body = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">PO Number</label>
                        <input type="text" id="poNumber" placeholder="PO-001" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Supplier</label>
                        <select id="poSupplier" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                            <option value="">-- Select Supplier --</option>
                            ${suppliers.map(s => `<option value="${s.name}">${s.name}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Amount</label>
                        <input type="number" id="poAmount" placeholder="0" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                    </div>
                </div>
            `;
            window.openModal('Add Purchase Order', body, () => {
                const poNumber = document.getElementById('poNumber').value.trim();
                const supplier = document.getElementById('poSupplier').value;
                const amount = parseFloat(document.getElementById('poAmount').value) || 0;

                if (!poNumber) { window.showAlert('error', 'PO Number is required'); return false; }
                if (!supplier) { window.showAlert('error', 'Please select a supplier'); return false; }
                if (amount <= 0) { window.showAlert('error', 'Amount must be greater than 0'); return false; }

                purchaseOrders.push({ id: Date.now(), poNumber, supplier, amount });
                setActiveTab('tabPO');
                window.closeModal();
                window.showAlert('success', 'Purchase order added successfully');
                return true;
            });
        }

        function setActiveTab(activeId) {
            const tabs = ['tabProducts', 'tabSuppliers', 'tabPO'];
            tabs.forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                if (id === activeId) {
                    el.classList.remove('text-slate-600');
                    el.classList.add('bg-green-50', 'text-green-700');
                } else {
                    el.classList.remove('bg-green-50', 'text-green-700');
                    el.classList.add('text-slate-600');
                }
            });

            const content = document.getElementById('invContent');
            const actionButton = document.getElementById('actionButton');

            if (activeId === 'tabProducts') {
                content.innerHTML = renderProducts();
                actionButton.innerHTML = renderButton('Add Product', 'openAddProductModal()');
            } else if (activeId === 'tabSuppliers') {
                content.innerHTML = renderSuppliers();
                actionButton.innerHTML = renderButton('Add Supplier', 'openAddSupplierModal()');
            } else if (activeId === 'tabPO') {
                content.innerHTML = renderPurchaseOrders();
                actionButton.innerHTML = renderButton('Add Purchase Order', 'openAddPOModal()');
            }
        }

        document.getElementById('tabProducts').addEventListener('click', () => {
            setActiveTab('tabProducts');
        });

        document.getElementById('tabSuppliers').addEventListener('click', () => {
            setActiveTab('tabSuppliers');
        });

        document.getElementById('tabPO').addEventListener('click', () => {
            setActiveTab('tabPO');
        });

        // initialize
        setActiveTab('tabProducts');
    </script>

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')
</body>

</html>
