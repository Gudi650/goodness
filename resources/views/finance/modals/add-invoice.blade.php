<div id="addInvoiceModal" class="hidden">
    <form id="invoiceForm" class="space-y-4">
        <input type="hidden" id="invoiceId" name="invoice_id" value="">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-medium">Invoice #</label>
                <input id="invoiceNumber" name="number" class="mt-1 block w-full rounded border px-3 py-2" type="text" />
            </div>

            <div>
                <label class="text-sm font-medium">Invoice Date</label>
                <input id="invoiceDate" name="date" class="mt-1 block w-full rounded border px-3 py-2" type="date" />
            </div>

            <div>
                <label class="text-sm font-medium">Due Date</label>
                <input id="invoiceDue" name="due_date" class="mt-1 block w-full rounded border px-3 py-2" type="date" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Client / Company</label>
                <select id="invoiceCompany" name="company_id" class="mt-1 block w-full rounded border px-3 py-2">
                    <option value="">Select company...</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Currency</label>
                <select id="invoiceCurrency" name="currency" class="mt-1 block w-full rounded border px-3 py-2">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Billing Address</label>
            <textarea id="invoiceAddress" name="billing_address" class="mt-1 block w-full rounded border px-3 py-2" rows="2"></textarea>
        </div>

        <!-- Line items -->
        <div>
            <label class="text-sm font-medium">Line Items</label>
            <div id="lineItems" class="mt-2 space-y-2">
                <div class="grid grid-cols-12 gap-2 items-center border rounded p-2 line-row">
                    <input class="col-span-5 rounded border px-2 py-1" name="description[]" placeholder="Description" />
                    <input class="col-span-2 rounded border px-2 py-1 qty" type="number" name="qty[]" min="0" step="1" placeholder="Qty" />
                    <input class="col-span-2 rounded border px-2 py-1 unit_price" type="number" name="unit_price[]" min="0" step="0.01" placeholder="Unit price" />
                    <input class="col-span-1 rounded border px-2 py-1 tax_rate" type="number" name="tax_rate[]" min="0" step="0.01" placeholder="Tax %" />
                    <div class="col-span-1 text-right mono line-total">0.00</div>
                    <button type="button" class="col-span-1 text-red-500 hover:text-red-700" onclick="removeLine(this)">✕</button>
                </div>
            </div>
            <button type="button" class="mt-2 text-sm text-brand-600" onclick="addLine()">+ Add item</button>
        </div>

        <!-- Totals -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Notes</label>
                <textarea id="invoiceNotes" name="notes" class="mt-1 block w-full rounded border px-3 py-2" rows="3"></textarea>
            </div>

            <div class="space-y-2 bg-slate-50 p-3 rounded">
                <div class="flex justify-between text-sm">
                    <div>Subtotal</div><div id="subtotal" class="mono">0.00</div>
                </div>
                <div class="flex justify-between text-sm items-center">
                    <div>Discount</div><div><input id="discount" name="discount" type="number" class="w-24 rounded border px-2 py-1" value="0" /></div>
                </div>
                <div class="flex justify-between text-sm">
                    <div>Taxes</div><div id="taxes" class="mono">0.00</div>
                </div>
                <div class="flex justify-between font-semibold">
                    <div>Total</div><div id="grandTotal" class="mono">0.00</div>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
(function () {
    const lineItems = document.getElementById('lineItems');
    const subtotalEl = document.getElementById('subtotal');
    const taxesEl = document.getElementById('taxes');
    const grandEl = document.getElementById('grandTotal');
    const discountEl = document.getElementById('discount');

    function parseNumber(v) {
        const n = parseFloat(v);
        return Number.isFinite(n) ? n : 0;
    }

    window.addLine = function () {
        const template = document.querySelector('.line-row');
        if (!template) return;
        const clone = template.cloneNode(true);
        // clear inputs
        clone.querySelectorAll('input').forEach(i => i.value = '');
        clone.querySelector('.line-total').textContent = '0.00';
        lineItems.appendChild(clone);
    }

    window.removeLine = function (btn) {
        const row = btn.closest('.line-row');
        if (!row) return;
        // if it's the only row, just clear it
        if (lineItems.querySelectorAll('.line-row').length === 1) {
            row.querySelectorAll('input').forEach(i => i.value = '');
            row.querySelector('.line-total').textContent = '0.00';
        } else {
            row.remove();
        }
        recomputeTotals();
    }

    function recomputeTotals() {
        let subtotal = 0;
        let taxes = 0;
        lineItems.querySelectorAll('.line-row').forEach(row => {
            const qty = parseNumber(row.querySelector('.qty')?.value);
            const unit = parseNumber(row.querySelector('.unit_price')?.value);
            const taxRate = parseNumber(row.querySelector('.tax_rate')?.value);
            const line = qty * unit;
            const lineTax = line * (taxRate / 100);
            subtotal += line;
            taxes += lineTax;
            const totalEl = row.querySelector('.line-total');
            if (totalEl) totalEl.textContent = line.toFixed(2);
        });

        const discount = parseNumber(discountEl?.value);
        const taxed = subtotal - discount + taxes;

        subtotalEl.textContent = subtotal.toFixed(2);
        taxesEl.textContent = taxes.toFixed(2);
        grandEl.textContent = taxed.toFixed(2);
    }

    // event delegation to recompute on input changes
    lineItems.addEventListener('input', function (e) {
        if (e.target.matches('.qty, .unit_price, .tax_rate')) {
            recomputeTotals();
        }
    });

    discountEl.addEventListener('input', recomputeTotals);

    // expose for external calls
    window.recomputeInvoiceTotals = recomputeTotals;

    // initial compute
    recomputeTotals();
})();
</script>