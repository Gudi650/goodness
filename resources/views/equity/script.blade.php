<script>

    // Initialize: hide everything except the default 'equity' tab
    document.addEventListener('DOMContentLoaded', function() {
        togglePane('equity');
    });

    // Initialize: hide everything except the default 'equity' tab
    document.addEventListener('DOMContentLoaded', function() {
        const equityBtn = document.querySelector('.tab-btn');
        switchTab('equity', equityBtn);
    });

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
            equity: document.getElementById('equityPane'),
            dividends: document.getElementById('dividendsPane'),
            'share-premium': document.getElementById('sharePremiumPane'),
        };

        Object.entries(panes).forEach(([tab, pane]) => {
            if (!pane) return;
            pane.classList.toggle('hidden', tab !== activeTab);
        });
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

        if (tab === 'equity') {
            sectionTitle.textContent = 'Equity';
            actionButton.innerHTML = renderButton('Add Equity', 'openInvoiceModal()');
            sectionButton.classList.add('hidden');

            return;
        }

        if (tab === 'dividends') {
            sectionTitle.textContent = 'Dividends';
            actionButton.innerHTML = renderButton('Add Dividend', 'openAddDividendModal()');
            sectionButton.classList.add('hidden');
            return;
        }

        if (tab === 'share-premium') {
            sectionTitle.textContent = 'Share Premium';
            actionButton.innerHTML = renderButton('Add Share Premium', 'openAddSharePremiumModal()');
            sectionButton.classList.add('hidden');
            return;
        }

        /*

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
        */
    }


</script>
