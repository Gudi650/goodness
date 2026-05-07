<div id="paymentModalBackdrop" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-start justify-center pt-6 pb-6 px-4">
    <div id="paymentModalCard" class="payment-modal-card bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-2xl mx-auto max-h-[92vh] flex flex-col">
        <!-- Sticky Header -->
        <div class="sticky top-0 bg-white z-10 px-6 pt-5 pb-4 border-b border-slate-100 flex items-center justify-between rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-brand-600 flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h10M5 6h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Zm3 4h.01M16 14h.01"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Record Payment</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Record an incoming or outgoing payment</p>
                </div>
            </div>
            <button type="button" onclick="closePaymentModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 transition-all flex items-center justify-center" aria-label="Close payment modal">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="paymentForm" method="POST" action="#" class="flex flex-col flex-1 overflow-hidden" enctype="multipart/form-data">
            @csrf

            <!-- Scrollable Body -->
            <div class="flex-1 overflow-y-auto px-6 py-5 space-y-6">
                <!-- Section 1: Payment Details -->
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-5 bg-brand-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Payment Details</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Payment Reference</label>
                            <input type="text" id="paymentId" name="payment_reference" placeholder="PAY-0000" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 font-mono text-slate-500 cursor-not-allowed focus:border-slate-200 focus:ring-0">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Payment Date</label>
                            <input type="date" id="paymentDate" name="payment_date" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Company / Received By</label>
                            <select id="paymentCompany" name="company" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                                <option value="">Select company...</option>
                                <option value="Goodness Agro Vet">Goodness Agro Vet</option>
                                <option value="Goodness Logistics">Goodness Logistics</option>
                                <option value="Goodness Properties">Goodness Properties</option>
                                <option value="Goodness Trading">Goodness Trading</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Payment Direction</label>
                            <select id="paymentDirection" name="payment_direction" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                                <option value="">Select direction...</option>
                                <option value="Incoming">Incoming (Money Received)</option>
                                <option value="Outgoing">Outgoing (Money Sent)</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Paid By / Received From</label>
                            <input type="text" id="paymentParty" name="party_name" placeholder="Client or supplier name" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                        </div>
                    </div>
                </section>

                <!-- Section 2: Payment Classification -->
                <section>
                    <div class="flex items-center gap-3 mb-4">
                                <div class="w-1 h-5 bg-brand-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Payment Classification</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Payment Method</label>
                            <select id="paymentMethod" name="payment_method" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                                <option value="">Select method...</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Mobile Money (M-Pesa)">Mobile Money (M-Pesa)</option>
                                <option value="Mobile Money (Tigopesa)">Mobile Money (Tigopesa)</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Direct Debit">Direct Debit</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Reference Number</label>
                            <input type="text" id="paymentReferenceNumber" name="reference_number" placeholder="Cheque no., M-Pesa code, bank ref..." class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Payment Category</label>
                            <select id="paymentCategory" name="payment_category" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                                <option value="">Select category...</option>
                                <option value="Invoice Settlement">Invoice Settlement</option>
                                <option value="Expense Reimbursement">Expense Reimbursement</option>
                                <option value="Loan Repayment">Loan Repayment</option>
                                <option value="Advance Payment">Advance Payment</option>
                                <option value="Refund">Refund</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Linked To</label>
                            <input type="text" id="paymentLinkedTo" name="linked_to" placeholder="INV-XXXX or EXP-XXXX (optional)" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                        </div>
                    </div>
                </section>

                <!-- Section 3: Amount -->
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-5 bg-brand-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Amount</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Amount</label>
                            <div class="flex items-stretch rounded-lg border border-slate-200 overflow-hidden focus-within:ring-2 focus-within:ring-brand-500/20 focus-within:border-brand-500">
                                <span class="inline-flex items-center border-r border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-500">TZS</span>
                                <input type="number" id="paymentAmount" name="amount" min="0" step="0.01" placeholder="0.00" class="w-full px-3 py-2.5 text-slate-700 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Currency</label>
                            <select id="paymentCurrency" name="currency" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                                <option value="TZS">TZS</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="KES">KES</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Exchange Rate</label>
                            <input type="number" id="paymentExchangeRate" name="exchange_rate" min="0" step="0.01" value="1.00" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                        </div>
                        <div id="paymentTzsEquivalentWrap" class="hidden">
                            <div class="h-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">TZS Equivalent</p>
                                    <p class="text-sm text-slate-500">Amount x Exchange Rate</p>
                                </div>
                                <div id="paymentTzsEquivalent" class="font-mono text-base font-semibold text-slate-800">0.00</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 4: Status & Notes -->
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-5 bg-brand-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Status & Notes</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="mb-1.5 flex items-center gap-2">
                                <span id="paymentStatusDot" class="w-2.5 h-2.5 rounded-full bg-brand-600"></span>
                                <label class="block text-sm font-medium text-slate-700">Payment Status</label>
                            </div>
                            <select id="paymentStatus" name="payment_status" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                                <option value="Completed">Completed</option>
                                <option value="Pending">Pending</option>
                                <option value="Failed">Failed</option>
                                <option value="Reversed">Reversed</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Notes</label>
                            <textarea id="paymentNotes" name="notes" rows="3" placeholder="Additional notes about this payment..." class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-slate-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"></textarea>
                        </div>
                    </div>
                </section>

                <!-- Section 5: Attachment -->
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-5 bg-brand-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Attachment</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>
                    <div class="space-y-3">
                        <!-- Upload zone -->
                        <div onclick="document.getElementById('paymentProofFile').click()"
                             class="border-2 border-dashed border-slate-200 rounded-xl p-5 flex flex-col items-center justify-center text-center hover:border-brand-500 hover:bg-brand-50/50 transition-all duration-200 cursor-pointer group">
                            <div class="w-10 h-10 rounded-full bg-slate-100 group-hover:bg-brand-100 flex items-center justify-center mb-3 transition-colors">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-600 group-hover:text-brand-700">Click to attach proof of payment</p>
                            <p class="text-xs text-slate-400 mt-1">Bank slip, M-Pesa screenshot — JPG, PNG, PDF</p>
                            <input type="file" id="paymentProofFile" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                        </div>

                        <!-- File preview area -->
                        <div id="paymentFilePreview" class="mt-3 hidden">
                            <div class="flex items-center gap-3 px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg">
                                <div id="paymentFilePreviewContent" class="min-w-0 flex-1"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Sticky Footer -->
            <div class="sticky bottom-0 bg-white border-t border-slate-100 px-6 py-4 rounded-b-2xl flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <button type="button" onclick="closePaymentModal()" class="px-4 py-2.5 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-all">
                    Cancel
                </button>
                <button type="submit" name="submit_mode" value="draft" class="px-4 py-2.5 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-all inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8l-5-5H5Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 13h10M7 17h6"/>
                    </svg>
                    Save as Draft
                </button>
                <button type="submit" name="submit_mode" value="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/>
                    </svg>
                    Record Payment
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes paymentModalIn {
        from { opacity: 0; transform: translateY(-12px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .payment-modal-card {
        animation: paymentModalIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<script>
    (function () {
        const companyOptions = [
            'Goodness Agro Vet',
            'Goodness Logistics',
            'Goodness Properties',
            'Goodness Trading',
        ];

        function randomPaymentReference() {
            const random = Math.floor(Math.random() * 9000) + 1000;
            return `PAY-${random}`;
        }

        function getPaymentField(id) {
            return document.getElementById(id);
        }

        function setStatusDot(status) {
            const dot = getPaymentField('paymentStatusDot');
            if (!dot) return;

            const colors = {
                Completed: 'bg-emerald-500',
                Pending: 'bg-amber-500',
                Failed: 'bg-red-500',
                Reversed: 'bg-slate-500',
            };

            dot.className = `w-2.5 h-2.5 rounded-full ${colors[status] || colors.Completed}`;
        }

        function updateTzsEquivalent() {
            const amountInput = getPaymentField('paymentAmount');
            const currencyInput = getPaymentField('paymentCurrency');
            const exchangeRateInput = getPaymentField('paymentExchangeRate');
            const tzsWrap = getPaymentField('paymentTzsEquivalentWrap');
            const tzsValue = getPaymentField('paymentTzsEquivalent');
            if (!amountInput || !currencyInput || !exchangeRateInput || !tzsWrap || !tzsValue) return;

            const amount = parseFloat(amountInput.value) || 0;
            const rate = parseFloat(exchangeRateInput.value) || 1;
            const currency = currencyInput.value;

            if (currency === 'TZS') {
                tzsWrap.classList.add('hidden');
                exchangeRateInput.value = '1.00';
                exchangeRateInput.setAttribute('readonly', 'readonly');
                exchangeRateInput.classList.add('bg-slate-50', 'cursor-not-allowed');
                tzsValue.textContent = '0.00';
                return;
            }

            tzsWrap.classList.remove('hidden');
            exchangeRateInput.removeAttribute('readonly');
            exchangeRateInput.classList.remove('bg-slate-50', 'cursor-not-allowed');
            tzsValue.textContent = (amount * rate).toFixed(2);
        }

        function updateFilePreview() {
            const input = getPaymentField('paymentProofFile');
            const preview = getPaymentField('paymentFilePreview');
            const content = getPaymentField('paymentFilePreviewContent');
            if (!input || !preview || !content) return;

            const file = input.files && input.files[0];
            if (!file) {
                preview.classList.add('hidden');
                content.innerHTML = '';
                return;
            }

            preview.classList.remove('hidden');

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    content.innerHTML = `
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="${event.target.result}" alt="Payment proof preview" class="h-12 w-12 rounded-lg object-cover border border-slate-200">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-700 truncate">${file.name}</p>
                                <p class="text-xs text-slate-400">Image selected</p>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
                return;
            }

            content.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21h10a2 2 0 0 0 2-2V7.828a2 2 0 0 0-.586-1.414l-3.828-3.828A2 2 0 0 0 13.172 2H7a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2Z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-700 truncate">${file.name}</p>
                        <p class="text-xs text-slate-400">${Math.round(file.size / 1024)} KB</p>
                    </div>
                </div>
            `;
        }

        function initializePaymentModal() {
            const paymentId = getPaymentField('paymentId');
            const paymentDate = getPaymentField('paymentDate');
            const paymentCurrency = getPaymentField('paymentCurrency');
            const paymentAmount = getPaymentField('paymentAmount');
            const paymentExchangeRate = getPaymentField('paymentExchangeRate');
            const paymentStatus = getPaymentField('paymentStatus');
            const paymentProofFile = getPaymentField('paymentProofFile');

            if (paymentId && !paymentId.value) {
                paymentId.value = randomPaymentReference();
            }

            if (paymentDate && !paymentDate.value) {
                paymentDate.value = new Date().toISOString().split('T')[0];
            }

            if (paymentCurrency && !paymentCurrency.value) {
                paymentCurrency.value = 'TZS';
            }

            if (paymentExchangeRate && !paymentExchangeRate.value) {
                paymentExchangeRate.value = '1.00';
            }

            if (paymentStatus && !paymentStatus.value) {
                paymentStatus.value = 'Completed';
            }

            setStatusDot(paymentStatus ? paymentStatus.value : 'Completed');
            updateTzsEquivalent();
            updateFilePreview();

            if (paymentAmount) {
                paymentAmount.addEventListener('input', updateTzsEquivalent);
            }

            if (paymentCurrency) {
                paymentCurrency.addEventListener('change', updateTzsEquivalent);
            }

            if (paymentExchangeRate) {
                paymentExchangeRate.addEventListener('input', updateTzsEquivalent);
            }

            if (paymentStatus) {
                paymentStatus.addEventListener('change', () => setStatusDot(paymentStatus.value));
            }

            if (paymentProofFile) {
                paymentProofFile.addEventListener('change', updateFilePreview);
            }
        }

        window.openPaymentModal = function () {
            const backdrop = getPaymentField('paymentModalBackdrop');
            const form = getPaymentField('paymentForm');
            if (!backdrop || !form) return;

            backdrop.classList.remove('hidden');
            initializePaymentModal();
        };

        window.closePaymentModal = function () {
            const backdrop = getPaymentField('paymentModalBackdrop');
            const form = getPaymentField('paymentForm');
            const preview = getPaymentField('paymentFilePreview');
            const content = getPaymentField('paymentFilePreviewContent');
            if (backdrop) backdrop.classList.add('hidden');
            if (form) form.reset();
            if (preview) preview.classList.add('hidden');
            if (content) content.innerHTML = '';
        };

        const backdrop = getPaymentField('paymentModalBackdrop');
        if (backdrop && !backdrop.dataset.bound) {
            backdrop.dataset.bound = 'true';
            backdrop.addEventListener('click', function (event) {
                if (event.target === this) {
                    closePaymentModal();
                }
            });
        }
    })();
</script>
