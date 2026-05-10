<div id="modalAddOrder" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 overflow-y-auto">
    <div class="bg-white rounded-lg max-w-6xl w-full m-4 my-8">
        <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between rounded-t-lg">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Add Order</h3>
                <p class="text-xs text-slate-500 mt-1">Create a new order</p>
            </div>
            <button onclick="closeLocalModal('modalAddOrder'); resetOrderForm();" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form id="orderForm" onsubmit="submitAddOrder(event)">
            <div class="px-6 py-5 max-h-[80vh] overflow-y-auto space-y-8">
                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 1 - Order Identity</h4>
                        <p class="text-xs text-slate-500 mt-1">Core order details and workflow status.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-sm text-slate-600">Order Number</label>
                            <input id="order_no" name="order_no" type="text" readonly value="ORD-0001" class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Order Date</label>
                            <input id="order_date" name="order_date" type="date" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Expected Delivery Date</label>
                            <input id="expected_delivery_date" name="expected_delivery_date" type="date" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Priority</label>
                            <select id="order_priority" name="priority" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Company</label>
                            <select id="order_company" name="company_id" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Company --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Branch / Department</label>
                            <select id="order_department" name="department_id" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Department --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Order Type</label>
                            <select id="order_type" name="order_type" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Sale">Sale</option>
                                <option value="Quotation">Quotation</option>
                                <option value="Proforma Invoice">Proforma Invoice</option>
                                <option value="Return">Return</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Status</label>
                            <select id="order_status" name="status" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Draft">Draft</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Processing">Processing</option>
                                <option value="Ready for Delivery">Ready for Delivery</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Returned">Returned</option>
                            </select>
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 2 - Customer Information</h4>
                        <p class="text-xs text-slate-500 mt-1">Select an existing customer and review the billing details.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Customer <span class="text-red-500">*</span></label>
                            <select id="order_customer" name="customer_id" required class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Customer --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Customer Name</label>
                            <input id="order_customer_name" type="text" readonly class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Customer Phone</label>
                            <input id="order_customer_phone" type="text" readonly class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Customer Email</label>
                            <input id="order_customer_email" type="email" readonly class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Billing Address</label>
                            <input id="order_billing_address" name="billing_address" type="text" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input id="same_as_billing" type="checkbox" class="rounded border-slate-300" onchange="toggleShippingAddress(this)" />
                                Same as billing address
                            </label>
                            <textarea id="order_shipping_address" name="shipping_address" rows="3" class="mt-2 block w-full border border-slate-200 rounded p-2" placeholder="Shipping address..."></textarea>
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-700">Section 3 - Order Items</h4>
                            <p class="text-xs text-slate-500 mt-1">Add one or more products to build the order.</p>
                        </div>
                        <button type="button" onclick="addOrderItemRow()" class="px-3 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add Item</button>
                    </div>

                    <div class="overflow-x-auto border border-slate-200 rounded-lg">
                        <table class="w-full min-w-[1200px]">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">#</th>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">Product</th>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">SKU</th>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">Description</th>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">Qty</th>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">UoM</th>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">Unit Price</th>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">Discount %</th>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">Total</th>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">Stock</th>
                                    <th class="px-3 py-2 text-left text-xs uppercase tracking-wider text-slate-500">Action</th>
                                </tr>
                            </thead>
                            <tbody id="orderItemsBody" class="divide-y divide-slate-100 bg-white">
                                <tr class="order-item-row">
                                    <td class="px-3 py-2 text-sm text-slate-600 item-number">1</td>
                                    <td class="px-3 py-2">
                                        <select class="order-product block w-full border border-slate-200 rounded p-2 text-sm" onchange="handleOrderProductChange(this)">
                                            <option value="">-- Select Product --</option>
                                        </select>
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="text" class="order-sku block w-full border border-slate-200 rounded p-2 text-sm bg-slate-50" readonly />
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="text" class="order-description block w-full border border-slate-200 rounded p-2 text-sm" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" min="1" value="1" class="order-qty block w-full border border-slate-200 rounded p-2 text-sm" oninput="recalculateOrderTotals()" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <select class="order-uom block w-full border border-slate-200 rounded p-2 text-sm">
                                            <option value="Piece">Piece</option>
                                            <option value="Box">Box</option>
                                            <option value="Kg">Kg</option>
                                            <option value="Litre">Litre</option>
                                        </select>
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" min="0" value="0" class="order-unit-price block w-full border border-slate-200 rounded p-2 text-sm" oninput="recalculateOrderTotals()" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" min="0" value="0" class="order-discount block w-full border border-slate-200 rounded p-2 text-sm" oninput="recalculateOrderTotals()" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="text" class="order-total block w-full border border-slate-200 rounded p-2 text-sm bg-slate-50" readonly value="0.00" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="space-y-1">
                                            <div class="order-stock text-xs text-slate-500">Available: 0</div>
                                            <div class="order-stock-warning text-xs text-red-600 hidden">Only 0 units available</div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button" class="remove-order-item inline-flex h-9 w-9 items-center justify-center rounded border border-slate-200 text-red-600 hover:bg-red-50 hidden" onclick="removeOrderItemRow(this)">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-6 0h6" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 4 - Financial Summary</h4>
                        <p class="text-xs text-slate-500 mt-1">All totals are calculated from the item rows and charges below.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-sm text-slate-600">Subtotal</label>
                            <input id="order_subtotal" type="text" readonly value="0.00" class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Overall Discount (%)</label>
                            <input id="order_discount_percent" type="number" min="0" value="0" oninput="recalculateOrderTotals()" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Discount Amount</label>
                            <input id="order_discount_amount" type="text" readonly value="0.00" class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                        <div class="flex items-end gap-3">
                            <label class="flex items-center gap-2 text-sm text-slate-600 mb-2">
                                <input id="vat_enabled" type="checkbox" checked onchange="recalculateOrderTotals()" class="rounded border-slate-300" />
                                VAT enabled
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Tax / VAT (%)</label>
                            <input id="order_vat_percent" type="number" min="0" value="18" oninput="recalculateOrderTotals()" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">VAT Amount</label>
                            <input id="order_vat_amount" type="text" readonly value="0.00" class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Shipping / Delivery Cost (TZS)</label>
                            <input id="order_shipping_cost" type="number" min="0" value="0" oninput="recalculateOrderTotals()" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Other Charges (TZS)</label>
                            <input id="order_other_charges" type="number" min="0" value="0" oninput="recalculateOrderTotals()" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div class="md:col-span-2 lg:col-span-4">
                            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-4 flex items-center justify-between gap-4 flex-wrap">
                                <div>
                                    <p class="text-sm font-medium text-emerald-700">Grand Total</p>
                                    <p class="text-xs text-emerald-600">Subtotal - Discount + VAT + Shipping + Other</p>
                                </div>
                                <div id="order_grand_total" class="text-2xl font-bold text-emerald-700">TZS 0.00</div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Amount Paid (TZS)</label>
                            <input id="order_amount_paid" type="number" min="0" value="0" oninput="recalculateOrderTotals()" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Balance Due</label>
                            <input id="order_balance_due" type="text" readonly value="0.00" class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 5 - Payment Information</h4>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Payment Status</label>
                            <select id="order_payment_status" name="payment_status" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Unpaid">Unpaid</option>
                                <option value="Partially Paid">Partially Paid</option>
                                <option value="Fully Paid">Fully Paid</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Payment Method</label>
                            <select id="order_payment_method" name="payment_method" onchange="toggleCreditTerms(this.value)" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Mobile Money M-Pesa">Mobile Money M-Pesa</option>
                                <option value="Mobile Money Tigopesa">Mobile Money Tigopesa</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Credit">Credit</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Payment Reference</label>
                            <input id="order_payment_reference" type="text" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Payment Date</label>
                            <input id="order_payment_date" type="date" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div id="creditTermsWrap" class="hidden">
                            <label class="block text-sm text-slate-600">Credit Terms</label>
                            <select id="order_credit_terms" class="mt-1 block w-full border border-slate-200 rounded p-2" onchange="calculateCreditDueDate()">
                                <option value="7">7 Days</option>
                                <option value="14">14 Days</option>
                                <option value="30">30 Days</option>
                                <option value="60">60 Days</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Credit Due Date</label>
                            <input id="order_credit_due_date" type="date" readonly class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 6 - Delivery Information</h4>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Delivery Method</label>
                            <select id="order_delivery_method" name="delivery_method" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Customer Pickup">Customer Pickup</option>
                                <option value="Company Delivery">Company Delivery</option>
                                <option value="Third Party Courier">Third Party Courier</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Delivery Date</label>
                            <input id="order_delivery_date" type="date" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Delivery Status</label>
                            <select id="order_delivery_status" name="delivery_status" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Not Dispatched">Not Dispatched</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Failed Delivery">Failed Delivery</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Driver / Delivery Person</label>
                            <input id="order_driver" type="text" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Vehicle / Plate Number</label>
                            <input id="order_vehicle" type="text" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-slate-600">Delivery Notes</label>
                            <textarea id="order_delivery_notes" rows="3" class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="Special delivery instructions..."></textarea>
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 7 - Sales Rep & Authorization</h4>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-sm text-slate-600">Sales Representative <span class="text-red-500">*</span></label>
                            <select id="order_sales_rep" name="sales_rep_id" required class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Sales Rep --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Approved By</label>
                            <select id="order_approved_by" name="approved_by" class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Approver --</option>
                                <option value="Manager">Manager</option>
                                <option value="Chairman">Chairman</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Commission %</label>
                            <input id="order_commission_percent" type="number" min="0" value="0" oninput="recalculateOrderTotals()" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Commission Amount</label>
                            <input id="order_commission_amount" type="text" readonly value="0.00" class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 8 - Attachments & Notes</h4>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm text-slate-600">LPO / Customer Purchase Order</label>
                            <input id="order_lpo" type="file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">File Preview</label>
                            <div id="orderFilePreview" class="mt-1 rounded border border-dashed border-slate-200 px-4 py-5 text-sm text-slate-500">No file selected.</div>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Internal Notes</label>
                            <textarea id="order_internal_notes" rows="3" class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="Staff-only remarks..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Customer Notes</label>
                            <textarea id="order_customer_notes" rows="3" class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="Remarks to print on invoice/delivery note..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-slate-600">Terms & Conditions</label>
                            <textarea id="order_terms_conditions" rows="4" class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="Return policy, warranty terms, and other conditions..."></textarea>
                        </div>
                    </div>
                </section>
            </div>

            <div class="px-6 py-4 flex gap-3 justify-end border-t border-slate-100">
                <button type="button" onclick="closeLocalModal('modalAddOrder'); resetOrderForm();" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium">Cancel</button>
                <button type="submit" id="submitOrderBtn" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">Add Order</button>
            </div>
        </form>
    </div>
</div>
