<div id="modalEditOrder" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 overflow-y-auto">
    <!-- Hidden container for product options -->
    <div id="productOptionsContainer" class="hidden">
        @isset($products)
            @foreach ($products as $product)
                <div class="product-option" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-sku="{{ $product->sku ?? '' }}">
                    {{ $product->name }}
                </div>
            @endforeach
        @endisset
    </div>

    <!-- Hidden container for customer options -->
    <div id="customerOptionsContainer" class="hidden">
        @isset($customers)
            @foreach ($customers as $customer)
                <div class="customer-option" data-id="{{ $customer->id }}" data-name="{{ $customer->customer_name ?? '' }}">
                    {{ $customer->customer_name ?? '' }}
                </div>
            @endforeach
        @endisset
    </div>

    <div class="bg-white rounded-lg max-w-6xl w-full m-4 my-8">
        <div
            class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between rounded-t-lg">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Edit Order</h3>
                <p class="text-xs text-slate-500 mt-1">Update order details</p>
            </div>
            <button onclick="closeLocalModal('modalEditOrder'); resetEditOrderForm();"
                class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form id="editOrderForm" onsubmit="submitEditOrder(event)">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 max-h-[80vh] overflow-y-auto space-y-8">
                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 1 - Order Identity</h4>
                        <p class="text-xs text-slate-500 mt-1">Core order details and workflow status.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-sm text-slate-600">Order Number</label>
                            <input id="edit_order_no" name="order_no" type="text" readonly
                                class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 text-slate-600" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Order Date</label>
                            <input id="edit_order_date" name="order_date" type="date"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Expected Delivery Date</label>
                            <input id="edit_expected_delivery_date" name="expected_delivery_date" type="date"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Priority</label>
                            <select id="edit_order_priority" name="priority"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Company</label>
                            <select id="edit_order_company" name="company_id"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Company --</option>
                                @isset($companies)
                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Branch / Department</label>
                            <select id="edit_order_department" name="department_id"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Department --</option>
                                @isset($departments)
                                    @foreach ($departments as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Order Type</label>
                            <select id="edit_order_type" name="order_type"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Sale">Sale</option>
                                <option value="Quotation">Quotation</option>
                                <option value="Proforma Invoice">Proforma Invoice</option>
                                <option value="Return">Return</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Status</label>
                            <select id="edit_order_status" name="status"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
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
                        <h4 class="text-sm font-semibold text-slate-700">Section 2 - Customer & Addresses</h4>
                        <p class="text-xs text-slate-500 mt-1">Customer information and delivery addresses.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm text-slate-600">Customer</label>
                            <select id="edit_order_customer" name="customer_id" onchange="handleOrderCustomerChange(this)"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Customer --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Billing Address</label>
                            <input id="edit_order_billing_address" name="billing_address" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="Enter billing address" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Shipping Address</label>
                            <input id="edit_order_shipping_address" name="shipping_address" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="Enter shipping address" />
                        </div>
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="edit_same_as_billing" onchange="toggleShippingAddress(this)"
                                    class="w-4 h-4 border border-slate-200 rounded" />
                                <span class="text-sm text-slate-600">Same as billing address</span>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 3 - Order Items</h4>
                        <p class="text-xs text-slate-500 mt-1">Items included in this order.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-3 py-2 text-left font-medium text-slate-500">#</th>
                                    <th class="px-3 py-2 text-left font-medium text-slate-500">Product</th>
                                    <th class="px-3 py-2 text-left font-medium text-slate-500">SKU</th>
                                    <th class="px-3 py-2 text-left font-medium text-slate-500">Description</th>
                                    <th class="px-3 py-2 text-left font-medium text-slate-500">Qty</th>
                                    <th class="px-3 py-2 text-left font-medium text-slate-500">UoM</th>
                                    <th class="px-3 py-2 text-left font-medium text-slate-500">Unit Price</th>
                                    <th class="px-3 py-2 text-left font-medium text-slate-500">Discount %</th>
                                    <th class="px-3 py-2 text-left font-medium text-slate-500">Total</th>
                                    <th class="px-3 py-2 text-center font-medium text-slate-500">Action</th>
                                </tr>
                            </thead>
                            <tbody id="editOrderItemsBody" class="divide-y divide-slate-100">
                            </tbody>
                        </table>
                    </div>
                    <button type="button" onclick="addOrderItemRow()" 
                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-sm font-medium">+ Add Item</button>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 4 - Financial Summary</h4>
                        <p class="text-xs text-slate-500 mt-1">Order totals and amounts.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Subtotal</label>
                            <input id="edit_order_subtotal" name="subtotal" type="number" step="0.01" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50" readonly />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Discount %</label>
                            <input id="edit_order_discount_percent" name="discount_percent" type="number" step="0.01" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" oninput="recalculateOrderTotals()" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Discount Amount</label>
                            <input id="edit_order_discount_amount" name="discount_amount" type="number" step="0.01" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50" readonly />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">VAT %</label>
                            <input id="edit_order_vat_percent" name="vat_percent" type="number" step="0.01" value="18"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" oninput="recalculateOrderTotals()" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">VAT Amount</label>
                            <input id="edit_order_vat_amount" name="vat_amount" type="number" step="0.01" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50" readonly />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Shipping Cost</label>
                            <input id="edit_order_shipping_cost" name="shipping_cost" type="number" step="0.01" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" oninput="recalculateOrderTotals()" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Other Charges</label>
                            <input id="edit_order_other_charges" name="other_charges" type="number" step="0.01" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" oninput="recalculateOrderTotals()" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Grand Total</label>
                            <input id="edit_order_grand_total" name="grand_total" type="number" step="0.01" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 font-bold text-slate-900" readonly />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Balance Due</label>
                            <input id="edit_order_balance_due" name="balance_due" type="number" step="0.01" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50 font-bold text-slate-900" readonly />
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 5 - Payment Information</h4>
                        <p class="text-xs text-slate-500 mt-1">Payment details and terms.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Payment Status</label>
                            <select id="edit_order_payment_status" name="payment_status"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Unpaid">Unpaid</option>
                                <option value="Partially Paid">Partially Paid</option>
                                <option value="Fully Paid">Fully Paid</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Payment Method</label>
                            <select id="edit_order_payment_method" name="payment_method" onchange="toggleCreditTerms(this)"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Method --</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Credit">Credit</option>
                                <option value="Mobile Money">Mobile Money</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Payment Date</label>
                            <input id="edit_order_payment_date" name="payment_date" type="date"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Amount Paid</label>
                            <input id="edit_order_amount_paid" name="amount_paid" type="number" step="0.01" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" oninput="recalculateOrderTotals()" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Payment Reference</label>
                            <input id="edit_order_payment_reference" name="payment_reference" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" placeholder="e.g., Check #, Receipt #" />
                        </div>
                        <div id="editCreditTermsWrap" class="hidden">
                            <label class="block text-sm text-slate-600">Credit Terms (Days)</label>
                            <input id="edit_order_credit_terms" name="credit_terms" type="number" value="30"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" oninput="calculateCreditDueDate()" />
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 6 - Delivery Information</h4>
                        <p class="text-xs text-slate-500 mt-1">Delivery method and tracking.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Delivery Method</label>
                            <select id="edit_order_delivery_method" name="delivery_method"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Method --</option>
                                <option value="Customer Pickup">Customer Pickup</option>
                                <option value="Delivery">Delivery</option>
                                <option value="Courier">Courier</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Delivery Date</label>
                            <input id="edit_order_delivery_date" name="delivery_date" type="date"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Delivery Status</label>
                            <select id="edit_order_delivery_status" name="delivery_status"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="Not Dispatched">Not Dispatched</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Failed Delivery">Failed Delivery</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Driver Name</label>
                            <input id="edit_order_driver_name" name="driver_name" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Vehicle Plate Number</label>
                            <input id="edit_order_vehicle_plate_number" name="vehicle_plate_number" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Delivery Notes</label>
                            <input id="edit_order_delivery_notes" name="delivery_notes" type="text"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" />
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 7 - Authorization & Commission</h4>
                        <p class="text-xs text-slate-500 mt-1">Sales rep, approver, and commission details.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm text-slate-600">Sales Rep</label>
                            <select id="edit_order_sales_rep" name="sales_rep_id" required
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- Select Sales Rep --</option>
                                @isset($users)
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Approved By</label>
                            <select id="edit_order_approved_by" name="approved_by"
                                class="mt-1 block w-full border border-slate-200 rounded p-2">
                                <option value="">-- No Approval (Optional) --</option>
                                @isset($users)
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role?->name ?? 'User' }})</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Commission %</label>
                            <input id="edit_order_commission_percent" type="number" min="0" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2" oninput="recalculateOrderTotals()" />
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Commission Amount</label>
                            <input id="edit_order_commission_amount" type="number" min="0" value="0"
                                class="mt-1 block w-full border border-slate-200 rounded p-2 bg-slate-50" readonly />
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700">Section 8 - Notes & Terms</h4>
                        <p class="text-xs text-slate-500 mt-1">Internal notes and terms & conditions.</p>
                    </div>
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm text-slate-600">Internal Notes</label>
                            <textarea id="edit_order_internal_notes" name="internal_notes" rows="3"
                                class="mt-1 block w-full border border-slate-200 rounded p-2"
                                placeholder="Internal notes for staff..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Customer Notes</label>
                            <textarea id="edit_order_customer_notes" name="customer_notes" rows="3"
                                class="mt-1 block w-full border border-slate-200 rounded p-2"
                                placeholder="Notes to include on invoice..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-600">Terms & Conditions</label>
                            <textarea id="edit_order_terms_and_conditions" name="terms_and_conditions" rows="3"
                                class="mt-1 block w-full border border-slate-200 rounded p-2"
                                placeholder="Terms & conditions..."></textarea>
                        </div>
                    </div>
                </section>
            </div>

            <div class="sticky bottom-0 bg-slate-50 border-t border-slate-200 px-6 py-4 flex justify-end gap-3 rounded-b-lg">
                <button type="button" onclick="closeLocalModal('modalEditOrder'); resetEditOrderForm();"
                    class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" id="submitEditOrderBtn"
                    class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium transition-colors">
                    Update Order
                </button>
            </div>
        </form>
    </div>
</div>
