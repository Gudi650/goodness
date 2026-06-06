<div id="invoiceModalBackdrop" class="hidden fixed inset-0 bg-slate-900 bg-opacity-50 z-50 flex items-start justify-center pt-10 pb-10 px-4 overflow-y-auto" onclick="if(event.target === this) closeInvoiceModal()">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-3xl">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-brand-600 rounded-sm"></div>
                <h2 class="text-xl font-semibold text-slate-800" style="font-family: Outfit, sans-serif;">New Invoice</h2>
            </div>
            <button type="button" onclick="closeInvoiceModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="invoiceForm" method="POST" action="/invoices" class="p-6 space-y-6 max-h-[calc(90vh-120px)] overflow-y-auto">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Invoice Number</label>
                    <input id="invoiceNumber" name="invoice_number" type="text" readonly class="w-full bg-slate-50 text-slate-400 px-3 py-2 rounded-md border border-slate-300 cursor-not-allowed" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Invoice Date</label>
                    <input id="invoiceDate" name="invoice_date" type="date" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Due Date</label>
                    <input id="invoiceDueDate" name="due_date" type="date" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select id="invoiceStatus" name="status" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        <option value="draft">Draft</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Company</label>
                    <select id="invoiceCompany" name="company_id" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        <option value="">Select company...</option>
                        @if (isset($companies))
                            @foreach ($companies as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Payment Method</label>
                    <select id="invoicePaymentMethod" name="payment_method" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="mobile">Mobile Money (M-Pesa / Tigopesa)</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Payment Method</label>
                    <select id="invoicePaymentMethod" name="payment_method" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="mobile">Mobile Money (M-Pesa / Tigopesa)</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Invoice type</label>
                    <select id="invoiceType" name="invoice_type" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Bank</label>
                    <select id="bank_id" name="bank_id" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        <option value="">Select bank...</option>
                        @foreach ($virtualAccounts as $account)
                            <option value="{{ $account['id'] }}" @selected(isset($currentBankId) && (string) $currentBankId === (string) $account['id'])>
                                {{ $account['account_name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="pt-4 border-t border-slate-200">
                <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Client Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Client Name <span class="text-red-500">*</span></label>
                        <input id="invoiceClientName" name="client_name" type="text" placeholder="e.g. Karibu Traders Ltd" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                        <span class="invoice-error text-red-500 text-xs mt-1 hidden">Client name is required</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Client Email</label>
                        <input id="invoiceClientEmail" name="client_email" type="email" placeholder="client@example.com" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Client Phone</label>
                        <input id="invoiceClientPhone" name="client_phone" type="text" placeholder="+255 7XX XXX XXX" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                    </div>
                    <div></div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Client Address</label>
                        <textarea id="invoiceClientAddress" name="client_address" placeholder="Street, City, Tanzania" rows="2" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent"></textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Invoice Items</h3>
                    <button type="button" onclick="addInvoiceItem()" class="flex items-center gap-2 text-sm px-3 py-1.5 text-brand-600 bg-brand-50 hover:bg-brand-100 rounded-md transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Item
                    </button>
                </div>

                <div id="invoiceItemsContainer" class="space-y-3">
                    <div class="invoice-item-row grid grid-cols-1 md:grid-cols-12 gap-3 md:gap-2 items-end p-3 border border-slate-200 rounded-md bg-white">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-slate-500 mb-1">#</label>
                            <div class="text-sm font-medium text-slate-700">1</div>
                        </div>
                        <div class="md:col-span-5">
                            <label class="block text-xs font-medium text-slate-700 mb-1">Description</label>
                            <input type="text" placeholder="Item description" class="invoice-item-desc w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                            <span class="invoice-item-error text-red-500 text-xs mt-1 hidden">Description required</span>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 mb-1">Qty</label>
                            <input type="number" min="1" step="1" value="1" class="invoice-item-qty w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 mb-1">Unit Price (TZS)</label>
                            <input type="number" min="0" step="0.01" placeholder="0.00" class="invoice-item-price w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                            <span class="invoice-item-error text-red-500 text-xs mt-1 hidden">Price required</span>
                        </div>
                        <div class="md:col-span-2 flex items-end gap-2">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-slate-700 mb-1">Total (TZS)</label>
                                <div class="invoice-item-total px-3 py-2 rounded-md border border-slate-200 bg-slate-50 text-slate-700 text-sm font-medium mono">0.00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200">
                <div class="w-full bg-slate-50 border border-slate-200 rounded-md p-4 space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Subtotal:</span>
                        <span id="invoiceSubtotal" class="font-medium text-slate-900 mono">0.00 TZS</span>
                    </div>
                    <div class="hidden flex justify-between items-center text-sm gap-3">
                        <span class="text-slate-600">Tax (%):</span>
                        <input id="invoiceTaxRate" type="number" min="0" step="0.01" value="18" class="w-20 px-2 py-1 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent text-right text-sm" />
                    </div>
                    <div class="hidden flex justify-between items-center text-sm">
                        <span class="text-slate-600">Tax Amount:</span>
                        <span id="invoiceTaxAmount" class="font-medium text-slate-900 mono">0.00 TZS</span>
                    </div>
                    <div class="flex justify-between items-center text-sm gap-3">
                        <span class="text-slate-600">Discount (TZS):</span>
                        <input id="invoiceDiscount" name="discount_amount" type="number" min="0" step="0.01" value="0" class="w-24 px-2 py-1 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent text-right text-sm" />
                    </div>
                    <div class="pt-2 border-t border-slate-300 flex justify-between items-center text-lg">
                        <span class="font-semibold text-slate-900">Total:</span>
                        <span id="invoiceTotalAmount" class="font-semibold text-brand-600 mono">0.00 TZS</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t-2 border-brand-200 flex justify-between items-center bg-brand-50 px-4 py-4 rounded-md">
                    <span class="text-lg font-bold text-slate-900">Overall Total:</span>
                    <span id="invoiceOverallTotal" class="text-2xl font-bold text-brand-600 mono">0.00 TZS</span>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200">
                <label class="block text-sm font-medium text-slate-700 mb-2">Notes / Additional Info</label>
                <textarea name="notes" id="invoiceNotes" placeholder="Payment terms, delivery instructions, or any additional notes..." rows="3" class="w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent"></textarea>
            </div>

            <input type="hidden" id="invoiceSubtotalHidden" name="subtotal" value="0">
            <input type="hidden" id="invoiceTaxAmountHidden" name="tax_amount" value="0">
            <input type="hidden" id="invoiceTotalAmountHidden" name="total_amount" value="0">
            <div id="invoiceItemsDataContainer"></div>
        </form>

        <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
            <button type="button" onclick="closeInvoiceModal()" class="px-4 py-2 rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors text-sm font-medium">Cancel</button>

            <button type="button" onclick="saveInvoiceAsDraft()" class="px-4 py-2 rounded-md border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium">Save as Draft</button>

            {{-- 
                <button type="button" onclick="sendInvoice()" class="flex items-center gap-2 px-4 py-2 rounded-md bg-brand-600 hover:bg-brand-700 text-white transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Send Invoice
                </button>
             --}}
             
        </div>
    </div>
</div>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    #invoiceModalBackdrop:not(.hidden) { animation: modalIn 0.2s ease-out; }
</style>

<script>
(function() {
    let itemCounter = 1;

    function syncTotals() {
        let subtotal = 0;
        document.querySelectorAll('.invoice-item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.invoice-item-qty').value) || 0;
            const price = parseFloat(row.querySelector('.invoice-item-price').value) || 0;
            const total = qty * price;
            row.querySelector('.invoice-item-total').textContent = total.toFixed(2);
            subtotal += total;
        });

        const taxRate = parseFloat(document.getElementById('invoiceTaxRate').value) || 0;
        const taxAmount = subtotal * (taxRate / 100);
        const discount = parseFloat(document.getElementById('invoiceDiscount').value) || 0;
        const grandTotal = subtotal + taxAmount - discount;

        document.getElementById('invoiceSubtotal').textContent = subtotal.toFixed(2) + ' TZS';
        document.getElementById('invoiceTaxAmount').textContent = taxAmount.toFixed(2) + ' TZS';
        document.getElementById('invoiceTotalAmount').textContent = grandTotal.toFixed(2) + ' TZS';
        document.getElementById('invoiceOverallTotal').textContent = grandTotal.toFixed(2) + ' TZS';
        document.getElementById('invoiceSubtotalHidden').value = subtotal.toFixed(2);
        document.getElementById('invoiceTaxAmountHidden').value = taxAmount.toFixed(2);
        document.getElementById('invoiceTotalAmountHidden').value = grandTotal.toFixed(2);
    }

    function attachItemListeners(row) {
        const inputs = row.querySelectorAll('.invoice-item-qty, .invoice-item-price, .invoice-item-desc');
        inputs.forEach(input => {
            input.addEventListener('input', syncTotals);
            input.addEventListener('blur', function() {
                if (this.classList.contains('invoice-item-price') && this.value) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
            });
        });

        document.getElementById('invoiceTaxRate').addEventListener('input', syncTotals);
        document.getElementById('invoiceDiscount').addEventListener('input', syncTotals);
    }

    function updateItemNumbers() {
        document.querySelectorAll('.invoice-item-row').forEach((row, idx) => {
            row.querySelector('div:first-child div:last-child').textContent = idx + 1;
        });
        itemCounter = document.querySelectorAll('.invoice-item-row').length;
    }

    function collectItemsAsHiddenInputs() {
        const container = document.getElementById('invoiceItemsDataContainer');
        container.innerHTML = '';

        document.querySelectorAll('.invoice-item-row').forEach((row, idx) => {
            const description = row.querySelector('.invoice-item-desc').value;
            const quantity = row.querySelector('.invoice-item-qty').value;
            const unitPrice = row.querySelector('.invoice-item-price').value;
            const totalPrice = (parseFloat(quantity) * parseFloat(unitPrice)) || 0;

            [
                ['item_number', idx + 1],
                ['description', description],
                ['quantity', quantity],
                ['unit_price', unitPrice],
                ['total_price', totalPrice.toFixed(2)],
            ].forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[${idx}][${key}]`;
                input.value = value;
                container.appendChild(input);
            });
        });
    }

    function validateForm() {
        const clientName = document.getElementById('invoiceClientName').value.trim();
        const invoiceDate = document.getElementById('invoiceDate').value;
        const dueDate = document.getElementById('invoiceDueDate').value;
        const company = document.getElementById('invoiceCompany').value;

        let valid = true;
        if (!clientName) valid = false;
        if (!invoiceDate) valid = false;
        if (!dueDate) valid = false;
        if (!company) valid = false;

        const rows = document.querySelectorAll('.invoice-item-row');
        if (!rows.length) valid = false;

        const hasValidItem = Array.from(rows).some(row => {
            const description = row.querySelector('.invoice-item-desc').value.trim();
            const price = parseFloat(row.querySelector('.invoice-item-price').value) || 0;
            return description && price > 0;
        });

        if (!hasValidItem) valid = false;

        if (!valid && window.showAlert) {
            window.showAlert('error', 'Please fill in all required fields.');
        }

        return valid;
    }

    window.openInvoiceModal = function() {
        // Open create modal; clear any editing state
        window.editingInvoiceId = null;
        document.getElementById('invoiceModalBackdrop').classList.remove('hidden');
        document.getElementById('invoiceForm').reset();
        document.getElementById('invoiceNumber').value = 'INV-' + Math.floor(Math.random() * 9000 + 1000);
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('invoiceDate').value = today;
        document.getElementById('invoiceDueDate').value = today;
        document.getElementById('invoiceTaxRate').value = 18;
        // remove any leftover hidden items container inputs
        document.getElementById('invoiceItemsDataContainer').innerHTML = '';
        syncTotals();
    };

    window.closeInvoiceModal = function() {
        document.getElementById('invoiceModalBackdrop').classList.add('hidden');
        // clear editing state when closing
        window.editingInvoiceId = null;
        // remove any _method override if injected
        const existingMethod = document.querySelector('#invoiceForm input[name="_method"]');
        if (existingMethod) existingMethod.remove();
    };

    window.addInvoiceItem = function() {
        const container = document.getElementById('invoiceItemsContainer');
        itemCounter++;

        const row = document.createElement('div');
        row.className = 'invoice-item-row grid grid-cols-1 md:grid-cols-12 gap-3 md:gap-2 items-end p-3 border border-slate-200 rounded-md bg-white';
        row.innerHTML = `
            <div class="md:col-span-1">
                <label class="block text-xs font-medium text-slate-500 mb-1">#</label>
                <div class="text-sm font-medium text-slate-700">${itemCounter}</div>
            </div>
            <div class="md:col-span-5">
                <label class="block text-xs font-medium text-slate-700 mb-1">Description</label>
                <input type="text" placeholder="Item description" class="invoice-item-desc w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                <span class="invoice-item-error text-red-500 text-xs mt-1 hidden">Description required</span>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-slate-700 mb-1">Qty</label>
                <input type="number" min="1" step="1" value="1" class="invoice-item-qty w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-slate-700 mb-1">Unit Price (TZS)</label>
                <input type="number" min="0" step="0.01" placeholder="0.00" class="invoice-item-price w-full px-3 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                <span class="invoice-item-error text-red-500 text-xs mt-1 hidden">Price required</span>
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-slate-700 mb-1">Total (TZS)</label>
                    <div class="invoice-item-total px-3 py-2 rounded-md border border-slate-200 bg-slate-50 text-slate-700 text-sm font-medium mono">0.00</div>
                </div>
                <button type="button" onclick="removeInvoiceItem(this)" class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        `;
        container.appendChild(row);
        attachItemListeners(row);
        syncTotals();
    };

    window.removeInvoiceItem = function(btn) {
        const row = btn.closest('.invoice-item-row');
        const container = document.getElementById('invoiceItemsContainer');
        if (container.querySelectorAll('.invoice-item-row').length > 1) {
            row.remove();
            updateItemNumbers();
            syncTotals();
        }
    };

    window.saveInvoiceAsDraft = function() {
        if (!validateForm()) return;
        collectItemsAsHiddenInputs();
        // If editing an existing invoice, send via AJAX PUT
        const form = document.getElementById('invoiceForm');
        if (window.editingInvoiceId) {
            // prepare FormData
            const fd = new FormData(form);
            fd.append('_method', 'PUT');
            fd.set('status', 'draft');

            // show loader if available
            const editLoader = document.getElementById('invoiceEditLoader');
            if (editLoader) editLoader.classList.remove('hidden');

            fetch(`/invoices/${window.editingInvoiceId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (editLoader) editLoader.classList.add('hidden');
                if (data.success) {
                    window.showAlert && window.showAlert('success', data.message || 'Invoice updated');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    window.showAlert && window.showAlert('error', data.message || 'Failed to update invoice');
                }
            })
            .catch(err => {
                if (editLoader) editLoader.classList.add('hidden');
                console.error(err);
                window.showAlert && window.showAlert('error', 'An error occurred while updating invoice');
            });
            return;
        }

        form.action = '/invoices/draft';
        form.submit();
    };

    window.sendInvoice = function() {
        if (!validateForm()) return;
        collectItemsAsHiddenInputs();
        const form = document.getElementById('invoiceForm');
        // If editing, submit via AJAX PUT to update endpoint
        if (window.editingInvoiceId) {
            const fd = new FormData(form);
            fd.append('_method', 'PUT');

            const editLoader = document.getElementById('invoiceEditLoader');
            if (editLoader) editLoader.classList.remove('hidden');

            fetch(`/invoices/${window.editingInvoiceId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (editLoader) editLoader.classList.add('hidden');
                if (data.success) {
                    window.showAlert && window.showAlert('success', data.message || 'Invoice updated');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    window.showAlert && window.showAlert('error', data.message || 'Failed to update invoice');
                }
            })
            .catch(err => {
                if (editLoader) editLoader.classList.add('hidden');
                console.error(err);
                window.showAlert && window.showAlert('error', 'An error occurred while updating invoice');
            });
            return;
        }

        form.action = '/invoices';
        form.submit();
    };

    document.addEventListener('DOMContentLoaded', function() {
        const firstRow = document.querySelector('.invoice-item-row');
        if (firstRow) {
            attachItemListeners(firstRow);
        }
    });
})();
</script>