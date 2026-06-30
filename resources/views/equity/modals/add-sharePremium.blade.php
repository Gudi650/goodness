<div id="addSharePremiumModal" class="hidden">
    <form method="POST" action="{{ route('share-premiums.store') }}" onsubmit="showEquityCreateLoader()"> 
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Company</label>
            <select name="company_id" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                <option value="">Select company...</option>
                @foreach($sharesDefinitions as $share)
                    <option value="{{ $share->company->id }}">{{ $share->company->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Nominal Value per Share</label>
            <input type="number" step="0.01" id="nominal_value" name="nominal_value"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Issue Price per Share</label>
            <input type="number" step="0.01" id="issue_price" name="issue_price"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Shares Issued</label>
            <input type="number" id="shares_issued" name="shares_issued"
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Total Premium</label>
            <input type="text" id="total_premium" readonly
                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm bg-slate-100">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Notes</label>
            <textarea name="notes" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm"></textarea>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save Premium</button>
    </form>
</div>

{{-- adding the loader when the user submits the datas as well here --}}
<x-loading id="sharePremiumCreateLoader" fullPage="true" class="hidden" />

<script>

    // Show loading indicator on form submit
    document.querySelector('#addSharePremiumModal form').addEventListener('submit', function() {
        document.getElementById('sharePremiumCreateLoader').classList.remove('hidden');
    });

    function showEquityCreateLoader() {
        const loader = document.getElementById('sharePremiumCreateLoader');
        if (loader) loader.classList.remove('hidden');
    }



</script>
