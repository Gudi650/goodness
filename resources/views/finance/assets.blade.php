<div id="assetsPane" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hidden">

    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <span class="text-base font-semibold text-slate-800">Recent Assets</span>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Code</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Term</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Value</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($assetsDetails as $asset)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $asset['code'] }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $asset['name'] }}</td>
                    <td class="px-4 py-3">
                        @if ($asset['term'] === 'Long-term')
                            <span
                                class="badge bg-purple-100 text-purple-700 border border-purple-200">{{ $asset['term'] }}</span>
                        @else
                            <span
                                class="badge bg-blue-100 text-blue-700 border border-blue-200">{{ $asset['term'] }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right font-mono">TZS {{ number_format($asset['current_value'], 0) }}</td>

                    <td class="px-4 py-3 text-right">

                        <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors"
                            title="Edit asset" aria-label="Edit asset"
                            onclick="openEditAssetModal({{ $asset['id'] }})">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487ZM16.862 4.487 19.5 7.125" />
                            </svg>
                        </button>

                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-3 text-center text-slate-500">No assets found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

<script>
    function openEditAssetModal(assetId) {
        console.log('[Asset] Opening edit modal for asset ID:', assetId);

        fetch(`/assets/${assetId}`)
            .then(response => response.json())
            .then(asset => {
                console.log('[Asset] Fetched asset details:', asset);

                // Open modal first with raw template HTML — no population yet
                const modal = document.getElementById('addAssetRevaluationModal');
                window.openModal('Asset Revaluation', modal.innerHTML, null, {
                    widthClass: 'max-w-3xl',
                    bodyClass: 'max-h-[calc(100vh-12rem)]',
                    hideFooter: true
                });

                // Populate and bind AFTER openModal finishes injecting into DOM
                setTimeout(function() {

                    const allCompany = document.querySelectorAll('[name="company_id"]');
                    const allAssetName = document.querySelectorAll('input[name="asset_name"]');
                    const allBook = document.querySelectorAll('#book_value');
                    const allRevalued = document.querySelectorAll('#revalued_amount');
                    const allSurplus = document.querySelectorAll('#surplus');
                    const allNotes = document.querySelectorAll('textarea[name="notes"]');
                    const allForms = document.querySelectorAll('#addAssetRevaluationForm'); // ADD THIS

                    const companySelect = allCompany[allCompany.length - 1];
                    const assetNameInput = allAssetName[allAssetName.length - 1];
                    const bookInput = allBook[allBook.length - 1];
                    const revaluedInput = allRevalued[allRevalued.length - 1];
                    const surplusInput = allSurplus[allSurplus.length - 1];
                    const notesInput = allNotes[allNotes.length - 1];
                    const form = allForms[allForms.length - 1]; // ADD THIS

                    if (!companySelect || !assetNameInput || !bookInput || !revaluedInput || !
                        surplusInput || !form) {
                        console.warn('[Asset] One or more inputs not found in DOM.');
                        return;
                    }

                    // Set form action dynamically using the fetched asset ID
                    form.action = `/assets/${asset.id}`;
                    console.log('[Asset] Form action set to:', form.action);

                    // Populate with fetched data
                    companySelect.value = asset.company_id;
                    assetNameInput.value = asset.name;
                    bookInput.value = asset.current_value;
                    revaluedInput.value = '';
                    if (notesInput) notesInput.value = '';

                    // Bind surplus calculator
                    function calculateSurplus() {
                        const book = parseFloat(bookInput.value) || 0;
                        const revalued = parseFloat(revaluedInput.value) || 0;
                        surplusInput.value = (revalued - book).toFixed(2);
                    }

                    bookInput.addEventListener('input', calculateSurplus);
                    revaluedInput.addEventListener('input', calculateSurplus);
                    calculateSurplus();

                    console.log('[Asset] Listeners bound successfully.');

                }, 0);
            })
            .catch(error => {
                console.error('[Asset] Failed to fetch asset details:', error);
            });
    }
</script>
