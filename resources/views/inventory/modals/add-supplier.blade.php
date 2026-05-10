<div id="modalAddSupplier" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 overflow-y-auto">
    <div class="bg-white rounded-lg max-w-5xl w-full m-4 my-8">
        <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between rounded-t-lg">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Add Supplier</h3>
                <p class="text-xs text-slate-500 mt-1">Capture supplier profile, payment, and compliance details</p>
            </div>
            <button onclick="closeLocalModal('modalAddSupplier')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form id="supplierForm" method="POST" action="{{ route('suppliers.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="px-6 py-5 space-y-6 max-h-[calc(100vh-220px)] overflow-y-auto">

                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 1: Supplier Identity</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Supplier ID</label>
                            <input id="supplierId" type="text" name="supplier_id" readonly placeholder="SUP-0001"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md bg-slate-50 text-slate-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Supplier Name <span class="text-red-500">*</span></label>
                            <input type="text" name="supplier_name" required placeholder="Supplier Inc"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Supplier Type <span class="text-red-500">*</span></label>
                            <select name="supplier_type" required class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select type</option>
                                <option value="Company">Company</option>
                                <option value="Individual">Individual</option>
                                <option value="Government">Government</option>
                                <option value="NGO">NGO</option>
                            </select>
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
                            <label class="block text-sm font-medium text-slate-700 mb-1">Registration Number / TIN</label>
                            <input type="text" name="registration_number" placeholder="TIN / Business registration"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Blacklisted">Blacklisted</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 2: Contact Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Contact Person Name <span class="text-red-500">*</span></label>
                            <input type="text" name="contact_person_name" required placeholder="John Doe"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Phone Number (+255) <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone_number" required placeholder="+2557XXXXXXXX"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Alternative Phone Number</label>
                            <input type="tel" name="alternative_phone_number" placeholder="+2557XXXXXXXX"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                            <input type="email" name="email" placeholder="contact@supplier.com"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Website</label>
                            <input type="url" name="website" placeholder="https://supplier.co.tz"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                    </div>
                </div>

                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 3: Location</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Country</label>
                            <input type="text" name="country" value="Tanzania"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Region</label>
                            <select name="region" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select region</option>
                                <option>Dar es Salaam</option>
                                <option>Mwanza</option>
                                <option>Arusha</option>
                                <option>Dodoma</option>
                                <option>Mbeya</option>
                                <option>Morogoro</option>
                                <option>Tanga</option>
                                <option>Kilimanjaro</option>
                                <option>Pwani</option>
                                <option>Tabora</option>
                                <option>Iringa</option>
                                <option>Shinyanga</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">District</label>
                            <input type="text" name="district" placeholder="District"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">P.O Box</label>
                            <input type="text" name="po_box" placeholder="P.O Box 123"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Street / Physical Address</label>
                            <textarea name="street_address" rows="2" placeholder="Street, building, landmarks"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>

                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 4: Supply Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Category Supplied</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 text-sm">
                                <label class="inline-flex items-center gap-2"><input type="checkbox" name="categories_supplied[]" value="Mining Equipment"> Mining Equipment</label>
                                <label class="inline-flex items-center gap-2"><input type="checkbox" name="categories_supplied[]" value="Agricultural Supplies"> Agricultural Supplies</label>
                                <label class="inline-flex items-center gap-2"><input type="checkbox" name="categories_supplied[]" value="Medical/Vet Supplies"> Medical/Vet Supplies</label>
                                <label class="inline-flex items-center gap-2"><input type="checkbox" name="categories_supplied[]" value="Office Supplies"> Office Supplies</label>
                                <label class="inline-flex items-center gap-2"><input type="checkbox" name="categories_supplied[]" value="Construction Materials"> Construction Materials</label>
                                <label class="inline-flex items-center gap-2"><input type="checkbox" name="categories_supplied[]" value="Electronics"> Electronics</label>
                                <label class="inline-flex items-center gap-2"><input type="checkbox" name="categories_supplied[]" value="Other"> Other</label>
                            </div>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Products Supplied</label>
                            <textarea name="products_supplied" rows="2" placeholder="List the main products"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Lead Time</label>
                            <select name="lead_time" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select lead time</option>
                                <option>1-3 days</option>
                                <option>4-7 days</option>
                                <option>1-2 weeks</option>
                                <option>3-4 weeks</option>
                                <option>Over a month</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Minimum Order Value (TZS)</label>
                            <input type="number" step="0.01" name="minimum_order_value" placeholder="0.00"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>

                        <!--
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Payment Terms</label>
                            <select name="payment_terms" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select payment terms</option>
                                <option>Cash on Delivery</option>
                                <option>7 Days Credit</option>
                                <option>14 Days Credit</option>
                                <option>30 Days Credit</option>
                                <option>60 Days Credit</option>
                            </select>
                        </div> -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Payment Terms</label>
                            <select name="payment_terms" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select payment terms</option>
                                <option>Cash on Delivery</option>
                                <option>7 Days Credit</option>
                                <option>14 Days Credit</option>
                                <option>30 Days Credit</option>
                                <option>60 Days Credit</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 5: Banking and Payment Details</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Bank Name</label>
                            <select name="bank_name" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select bank</option>
                                <option>CRDB</option>
                                <option>NMB</option>
                                <option>Stanbic</option>
                                <option>NBC</option>
                                <option>Absa</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Account Name</label>
                            <input type="text" name="account_name" placeholder="Supplier account name"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Account Number</label>
                            <input type="text" name="account_number" placeholder="Account number"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Branch Name</label>
                            <input type="text" name="branch_name" placeholder="Branch"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Mobile Money Number</label>
                            <input type="tel" name="mobile_money_number" placeholder="M-Pesa / Tigopesa number"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Preferred Payment Method</label>
                            <select name="preferred_payment_method" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select payment method</option>
                                <option>Bank Transfer</option>
                                <option>Cash</option>
                                <option>Mobile Money</option>
                                <option>Cheque</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 6: Performance and Notes</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Rating</label>
                            <select name="rating" class="w-full px-3 py-2 border border-slate-300 rounded-md">
                                <option value="">Select rating</option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Contract Start Date</label>
                            <input type="date" name="contract_start_date"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Contract End Date</label>
                            <input type="date" name="contract_end_date"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Notes / Additional Info</label>
                            <textarea name="notes" rows="3" placeholder="Special terms, conditions, remarks"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Section 7: Documents (Optional)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Business Registration Certificate</label>
                            <input type="file" name="business_registration_certificate" accept=".pdf,.jpg,.jpeg,.png"
                                onchange="updateSupplierFilePreview(this, 'businessRegPreview')"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                            <div id="businessRegPreview" class="mt-2 text-xs text-slate-500">No file selected</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">TIN Certificate</label>
                            <input type="file" name="tin_certificate" accept=".pdf,.jpg,.jpeg,.png"
                                onchange="updateSupplierFilePreview(this, 'tinCertPreview')"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md">
                            <div id="tinCertPreview" class="mt-2 text-xs text-slate-500">No file selected</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 bg-slate-50 border-t border-slate-200 px-6 py-4 flex justify-between items-center rounded-b-lg">
                <p class="text-xs text-slate-500"><span class="text-red-500">*</span> required fields</p>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeLocalModal('modalAddSupplier')" class="px-4 py-2 border rounded-md">Cancel</button>
                    <div class="flex items-center gap-3" id="supplier-save-wrap">
                        <button type="submit" id="submitSupplierBtn" class="px-4 py-2 bg-brand-600 text-white rounded-md">Save</button>
                    </div>
                </div>
            </div>
            @include('components.loading', [
                'id' => 'supplierSaveLoading',
                'show' => false,
                'fullPage' => true,
            ])
        </form>
    </div>
</div>
