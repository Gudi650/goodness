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

        window.openModal('Add Equity', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });

        // Wait for openModal to finish injecting HTML into the DOM
        setTimeout(function() {
            bindSharesListener();
        }, 0);
    }

    //open the add company shares definition modal
    function openAddCompanySharesModal() {
        const body = document.getElementById('addCompanySharesModal').innerHTML;

        window.openModal('Add Company Shares', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });
    }

    //open the add share premium modal
    function openAddSharePremiumModal() {
        const body = document.getElementById('addSharePremiumModal').innerHTML;

        window.openModal('Add Share Premium', body, null, {
            widthClass: 'max-w-3xl',
            bodyClass: 'max-h-[calc(100vh-12rem)]',
            hideFooter: true
        });
    }

    //switch tab navigation
    function switchTab(tab, btnEl) {
        const tabBtns = document.querySelectorAll('.tab-btn');

        tabBtns.forEach(btn => {
            btn.classList.remove('text-slate-700', 'border-brand-600', 'border-b-2');
            btn.classList.add('text-slate-500');
        });

        if (btnEl) {
            btnEl.classList.remove('text-slate-500');
            btnEl.classList.add('text-slate-700', 'border-brand-600', 'border-b-2');
        }

        togglePane(tab);

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
            sectionButton.innerHTML = renderSectionButton('Issue Dividends', 'openDividendModal()');
            sectionButton.classList.remove('hidden');
            return;
        }

        if (tab === 'share-premium') {
            sectionTitle.textContent = 'Share Premium';
            actionButton.innerHTML = renderButton('Add Share Premium', 'openAddSharePremiumModal()');
            //sectionButton.innerHTML = renderSectionButton('Issue Dividends', 'openDividendModal()');
            sectionButton.classList.add('hidden');
            return;
        }

        if (tab === 'companyShares') {
            sectionTitle.textContent = 'Company Shares';
            actionButton.innerHTML = renderButton('Add Company Shares', 'openAddCompanySharesModal()');
            //sectionButton.innerHTML = renderSectionButton('Issue Dividends', 'openDividendModal()');
            sectionButton.classList.add('hidden');
            return;
        }
    }

    // Binds the ownership % calculation to the cloned modal inputs
    // Uses sharesDefinitionsMap embedded by Blade to get the real issued_shares per company
    function bindSharesListener() {

        const allShares = document.querySelectorAll('#shares');
        const allOwnership = document.querySelectorAll('#ownership_percentage');
        const allCompany = document.querySelectorAll('[name="company_id"]');
        const allValue = document.querySelectorAll('#share_value'); // ADD THIS

        console.log('[Equity] Total #shares in DOM:', allShares.length);

        const sharesInput = allShares[allShares.length - 1];
        const ownershipInput = allOwnership[allOwnership.length - 1];
        const companySelect = allCompany[allCompany.length - 1];
        const valueInput = allValue[allValue.length - 1]; // ADD THIS

        console.log('[Equity] sharesInput:', sharesInput);
        console.log('[Equity] ownershipInput:', ownershipInput);
        console.log('[Equity] companySelect:', companySelect);
        console.log('[Equity] valueInput:', valueInput); // ADD THIS

        if (!sharesInput || !ownershipInput || !companySelect || !valueInput) {
            console.warn('[Equity] One or more inputs not found in DOM.');
            return;
        }

        // Make value readonly since it's auto-calculated
        valueInput.setAttribute('readonly', true);
        valueInput.classList.add('bg-slate-50', 'cursor-not-allowed');

        function recalculate() {
            const companyId = companySelect.value;
            console.log('[Equity] Selected company_id:', companyId);

            const definition = sharesDefinitionsMap[companyId];
            console.log('[Equity] Matching definition:', definition);

            if (!definition) {
                console.warn('[Equity] No shares definition found for company:', companyId);
                ownershipInput.value = '';
                valueInput.value = '';
                return;
            }

            const totalShares = definition.issued_shares;
            const shareValuePerShare = definition.share_value;
            console.log('[Equity] Total issued shares:', totalShares);
            console.log('[Equity] Share value per share:', shareValuePerShare);

            const sharesValue = parseFloat(sharesInput.value) || 0;
            console.log('[Equity] Shares typed:', sharesValue);

            if (totalShares <= 0) {
                console.warn('[Equity] issued_shares is 0 for this company.');
                ownershipInput.value = '0.00';
                valueInput.value = '0.00';
                return;
            }

            // Calculate ownership %
            const ownership = ((sharesValue / totalShares) * 100).toFixed(2);
            console.log('[Equity] Ownership %:', ownership);
            ownershipInput.value = ownership;

            // Calculate value held
            const valueHeld = (sharesValue * shareValuePerShare).toFixed(2);
            console.log('[Equity] Value Held (TZS):', valueHeld);
            valueInput.value = valueHeld;
        }

        sharesInput.addEventListener('input', recalculate);
        companySelect.addEventListener('change', recalculate);

        console.log('[Equity] Listeners bound successfully.');
    }
    
</script>
