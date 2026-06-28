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
            companyShares: document.getElementById('companySharesPane')


        };

        Object.entries(panes).forEach(([tab, pane]) => {
            if (!pane) return;
            pane.classList.toggle('hidden', tab !== activeTab);
        });

    }

    //open equity modal
    function openEquityModal() {
        const body = document.getElementById('addEquityModal').innerHTML;

        console.log('Opening modal with body:', body); // Debugging line

        window.openModal('Add Equity', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });

        // Wait for openModal to finish injecting HTML into the DOM
        setTimeout(function() {
            calculateOwnershipPercentage();
        }, 0);

    }

    //open the add company shares defintion modal
    function openAddCompanySharesModal() {
        const body = document.getElementById('addCompanySharesModal').innerHTML;

        console.log('Opening modal with body:', body); // Debugging line

        window.openModal('Add Company Shares', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
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
            actionButton.innerHTML = renderButton('Add Equity', 'openEquityModal()');
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

        if (tab === 'companyShares') {
            sectionTitle.textContent = 'Company Shares';
            actionButton.innerHTML = renderButton('Add Company Shares', 'openAddCompanySharesModal()');
            sectionButton.innerHTML = renderSectionButton('Issue Dividends', 'openDividendModal()');
            sectionButton.classList.remove('hidden');
            return;
        }

    }

    //function to calculate the ownership percentage based on the shares issued and the total shares
    //It will calculate only when the user is typing the shares input field that when it will be called
    function calculateOwnershipPercentage() {

        // Get ALL elements with id="shares" — grab the last one (the cloned modal copy)
        const allShares = document.querySelectorAll('#shares');
        const allOwnership = document.querySelectorAll('#ownership_percentage');

        console.log('[Equity] All #shares found:', allShares);
        console.log('[Equity] Total #shares in DOM:', allShares.length);

        // The last one is always the cloned modal copy
        const sharesInput = allShares[allShares.length - 1];
        const ownershipInput = allOwnership[allOwnership.length - 1];

        console.log('[Equity] sharesInput element:', sharesInput);

        if (!sharesInput || !ownershipInput) {
            console.warn('[Equity] Inputs not found in DOM.');
            return;
        }

        const totalShares = 100000;

        // addEventListener goes on the ELEMENT
        sharesInput.addEventListener('input', function() {

            // Read .value INSIDE the handler, every time user types
            const sharesValue = parseFloat(sharesInput.value) || 0;
            console.log('[Equity] Shares typed:', sharesValue);

            const ownership = ((sharesValue / totalShares) * 100).toFixed(2);
            console.log('[Equity] Ownership %:', ownership);

            // Set result on the ELEMENT
            ownershipInput.value = ownership;
        });

        console.log('[Equity] Listener bound successfully.');
    }
    
</script>
