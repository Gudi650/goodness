<div id="modalAddPO" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 overflow-y-auto">
    <div class="bg-white rounded-lg max-w-4xl w-full m-4 my-8">
        <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between rounded-t-lg">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Add Purchase Order</h3>
                <p class="text-xs text-slate-500 mt-1">Create a new purchase order with supplier and item details</p>
            </div>
            <button onclick="closeLocalModal('modalAddPO')" class="text-slate-400 hover:text-slate-600">?</button>
        </div>

        <form id="poForm" method="POST" action="{{ route('purchase-orders.store') }}" class="space-y-0">
            @csrf
            <div class="px-6 py-5 space-y-6 max-h-[calc(100vh-220px)] overflow-y-auto">

                <!-- SECTION 1: Purchase Order Identity -->
                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 1: Purchase Order Identity</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">PO Number</label>
                            <input id="poNumber" type="text" name="po_number" readonly placeholder="PO-0001"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md bg-slate-50 text-slate-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">PO Date</label>
                            <input type="date" name="po_date" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Expected Delivery Date <span class="text-red-500">*</span></label>
                            <input type="date" name="expected_delivery_date" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Company <span class="text-red-500">*</span></label>
                            <select name="company_id" required class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select Company</option>
                                @if (isset($companies))
                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                            <select name="department_id" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select Department</option>
                                @if (isset($departments))
                                    @foreach ($departments as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Priority Level</label>
                            <select name="priority_level" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="Normal">Normal</option>
                                <option value="Low">Low</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="Draft">Draft</option>
                                <option value="Pending Approval">Pending Approval</option>
                                <option value="Approved">Approved</option>
                                <option value="Ordered">Ordered</option>
                                <option value="Partially Received">Partially Received</option>
                                <option value="Fully Received">Fully Received</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Supplier Information -->
                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 2: Supplier Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                            <select id="supplierSelect" name="supplier_id" required onchange="updateSupplierInfo()"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select Supplier</option>
                                @if (isset($suppliers))
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" data-contact="{{ $supplier->contact_person_name }}" data-phone="{{ $supplier->phone_number }}">
                                            {{ $supplier->supplier_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Supplier Contact Person</label>
                            <input id="supplierContact" type="text" readonly
                                class="w-full px-3 py-2 border border-slate-300 rounded-md bg-slate-50 text-slate-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Supplier Phone</label>
                            <input id="supplierPhone" type="text" readonly
                                class="w-full px-3 py-2 border border-slate-300 rounded-md bg-slate-50 text-slate-600">
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Delivery Address</label>
                            <textarea name="delivery_address" rows="3"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Delivery Method</label>
                            <select name="delivery_method" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="Supplier Delivers">Supplier Delivers</option>
                                <option value="Self Collection">Self Collection</option>
                                <option value="Third Party Courier">Third Party Courier</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Order Items -->
                <div class="border-b border-slate-200 pb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-slate-700">Section 3: Order Items</h4>
                        <button type="button" onclick="addOrderItem()" class="px-3 py-1 text-xs bg-brand-600 text-white rounded hover:bg-brand-700">
                            + Add Item
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-2 py-2 text-left">#</th>
                                    <th class="px-2 py-2 text-left">Product Name</th>
                                    <th class="px-2 py-2 text-left">SKU</th>
                                    <th class="px-2 py-2 text-right">Qty</th>
                                    <th class="px-2 py-2 text-left">UOM</th>
                                    <th class="px-2 py-2 text-right">Unit Price</th>
                                    <th class="px-2 py-2 text-right">Total</th>
                                    <th class="px-2 py-2 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="orderItemsBody" class="divide-y divide-slate-200">
                                <!-- Items added dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION 4: Financial Summary -->
                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 4: Financial Summary</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Subtotal (TZS)</label>
                            <input id="subtotal" type="text" readonly value="0.00"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md bg-slate-50 text-slate-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Discount (%)</label>
                            <input type="number" name="discount_percent" step="0.01" value="0" onchange="calculateTotals()"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Discount Amount (TZS)</label>
                            <input id="discountAmount" type="text" readonly value="0.00"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md bg-slate-50 text-slate-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tax / VAT (%)</label>
                            <input type="number" name="vat_percent" step="0.01" value="18" onchange="calculateTotals()"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">VAT Amount (TZS)</label>
                            <input id="vatAmount" type="text" readonly value="0.00"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md bg-slate-50 text-slate-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Shipping / Delivery Cost (TZS)</label>
                            <input type="number" name="shipping_cost" step="0.01" value="0" onchange="calculateTotals()"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Total Amount (TZS)</label>
                            <input id="totalAmount" type="text" readonly value="0.00"
                                class="w-full px-3 py-2 border-2 border-brand-600 rounded-md bg-brand-50 text-brand-900 font-bold text-lg">
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: Payment Information -->
                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 5: Payment Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Payment Terms</label>
                            <select name="payment_terms" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="7 Days">7 Days</option>
                                <option value="14 Days">14 Days</option>
                                <option value="30 Days">30 Days</option>
                                <option value="60 Days">60 Days</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Payment Method</label>
                            <select name="payment_method" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Mobile Money">Mobile Money</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Deposit / Advance Payment (TZS)</label>
                            <input type="number" name="deposit_amount" step="0.01" value="0" onchange="calculateTotals()"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Balance Due (TZS)</label>
                            <input id="balanceDue" type="text" readonly value="0.00"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md bg-slate-50 text-slate-600">
                        </div>
                    </div>
                </div>

                <!-- SECTION 6: Approval & Authorization -->
                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 6: Approval & Authorization</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Requested By</label>
                            <select name="requested_by" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select Staff Member</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Approved By</label>
                            <select name="approved_by" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select Manager</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Approval Date</label>
                            <input type="date" name="approval_date"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Authorization Notes</label>
                            <textarea name="authorization_notes" rows="2"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 7: Attachments & Notes -->
                <div>
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 7: Attachments & Notes</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Supporting Document</label>
                            <input type="file" name="supporting_document" accept=".pdf,.jpg,.jpeg,.png" 
                                onchange="updateDocumentPreview(event)"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                            <div id="documentPreview" class="mt-2 text-xs text-slate-500">No file selected</div>
                        </div>
                        <div></div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Internal Notes</label>
                            <textarea name="internal_notes" rows="2"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md"></textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Terms & Conditions</label>
                            <textarea name="terms_and_conditions" rows="2"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer: Submit Buttons -->
            <div class="sticky bottom-0 border-t border-slate-200 bg-white px-6 py-3 flex justify-end gap-2 rounded-b-lg">
                <button type="button" onclick="closeLocalModal('modalAddPO')" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm font-medium">
                    Save Purchase Order
                </button>
            </div>
        </form>
    </div>
</div>
