<div id="modalAddProduct" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 overflow-y-auto">
    <div class="bg-white rounded-lg max-w-3xl w-full m-4 my-8">
        <!-- Modal Header -->
        <div
            class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between rounded-t-lg">
            <div>
                <h3 class="text-xl font-semibold font-display text-slate-900">Add New Product</h3>
                <p class="text-xs text-slate-500 mt-1">Fill in all product details below</p>
            </div>
            <button onclick="closeLocalModal('modalAddProduct')"
                class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-md transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="productForm" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="productFormMethod" value="POST">
            <input type="hidden" name="product_id" id="product_id" value="">
            <div class="px-6 py-5 space-y-6 max-h-[calc(100vh-240px)] overflow-y-auto">

                <!-- Product Image Upload Section
                <div class="border-2 border-dashed border-slate-200 rounded-lg p-4 bg-slate-50/50">
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-20 h-20 bg-slate-200 rounded-lg border border-slate-300 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Product Image</label>
                            <input type="file" name="product_image" accept="image/*"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-brand-100 file:text-brand-700 hover:file:bg-brand-200">
                            <p class="text-xs text-slate-500 mt-1">PNG, JPG or GIF (Max 5MB)</p>
                        </div>
                    </div>
                </div>
                -->

                <!-- Section 1: Basic Product Information -->
                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-brand-100 text-brand-600 text-xs font-bold">1</span>
                        Basic Information
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Inventory ID</label>
                            <input type="text" name="inventory_id" placeholder="Auto-generated" disabled
                                class="w-full px-3 py-2 border border-slate-300 rounded-md bg-slate-50 text-slate-600">
                            <p class="text-xs text-slate-500 mt-1">Auto-generated on save</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Product Name <span
                                    class="text-red-500">*</span></label>
                            <input id="product_name" type="text" name="name" placeholder="e.g., Hydraulic Drill Bit" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">SKU <span
                                    class="text-red-500">*</span></label>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="sku_auto_generate" id="skuAuto" class="h-4 w-4" checked>
                                <label for="skuAuto" class="text-xs text-slate-500">Auto-generate SKU
                                    (recommended)</label>
                            </div>
                            <input id="product_sku" type="text" name="sku" placeholder="e.g., MIN-DRL-001" required
                                class="w-full mt-2 px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                            <p class="text-xs text-slate-500 mt-1">Leave checked to auto-generate; uncheck to enter
                                manually.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Barcode / Serial Number</label>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="barcode_auto_generate" id="barAuto" class="h-4 w-4"
                                    checked>
                                <label for="barAuto" class="text-xs text-slate-500">Auto-generate Barcode</label>
                            </div>
                            <input id="product_barcode" type="text" name="barcode" placeholder="e.g., HDB-2024-001285"
                                class="w-full mt-2 px-3 py-2 border border-slate-300 rounded-md font-mono text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                            <p class="text-xs text-slate-500 mt-1">Auto-generated barcodes start with
                                <strong>BAR</strong>. Uncheck to type manually.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Classification & Company -->
                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-brand-100 text-brand-600 text-xs font-bold">2</span>
                        Classification
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Category <span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-2 items-center mb-2">
                                <select id="product_category" name="category" required
                                    class="flex-1 px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                    <option value="">Select Category</option>
                                    <option value="Mining Equipment">Mining Equipment</option>
                                    <option value="Agro-Vet Medicine">Agro-Vet Medicine</option>
                                    <option value="Seeds">Seeds</option>
                                    <option value="Fertilizer">Fertilizer</option>
                                    <option value="Tools">Tools</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <!--
                            <div id="new-category-wrap" class="hidden mt-2">
                                <div class="flex gap-2">
                                    <input type="text" name="new_category" placeholder="New category name"
                                        class="flex-1 px-3 py-2 border border-slate-300 rounded-md">
                                    <button type="button" id="btn-add-category-confirm" class="px-3 py-2 bg-brand-600 text-white rounded-md">Add</button>
                                </div>
                            </div>
                            -->
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Brand / Manufacturer</label>
                            <input id="product_brand" type="text" name="brand" placeholder="e.g., Bosch Industrial"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Company <span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-2 items-center mb-2">
                                <select id="product_company" name="company" required
                                    class="flex-1 px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                    <option value="">Select Company</option>
                                    @if (isset($companies))
                                        @foreach ($companies as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                    
                                </select>
                            </div>
                            <div id="new-company-wrap" class="hidden mt-2">
                                <div class="flex gap-2">
                                    <input type="text" name="new_company" placeholder="New company name"
                                        class="flex-1 px-3 py-2 border border-slate-300 rounded-md">
                                    <button type="button" id="btn-add-company-confirm"
                                        class="px-3 py-2 bg-brand-600 text-white rounded-md">Add</button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Status <span
                                    class="text-red-500">*</span></label>
                            <select id="product_status" name="status" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Discontinued">Discontinued</option>
                                <option value="Out of Stock">Out of Stock</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Stock & Inventory -->
                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-brand-100 text-brand-600 text-xs font-bold">3</span>
                        Stock & Inventory
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Quantity in Stock <span
                                    class="text-red-500">*</span></label>
                            <input id="product_stock" type="number" name="stock" placeholder="0" min="0" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Unit of Measure <span
                                    class="text-red-500">*</span></label>
                            <select id="product_unit_of_measure" name="unit_of_measure" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                <option value="">Select Unit</option>
                                <option value="pieces">Pieces</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="liters">Liters</option>
                                <option value="meters">Meters (m)</option>
                                <option value="bags">Bags</option>
                                <option value="boxes">Boxes</option>
                                <option value="drums">Drums</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Reorder Level <span
                                    class="text-red-500">*</span></label>
                            <input id="product_reorder_level" type="number" name="reorder_level" placeholder="40" min="0" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                            <p class="text-xs text-slate-500 mt-1">Alert when qty falls below this</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Location <span
                                    class="text-red-500">*</span></label>
                            <input id="product_location" type="text" name="location" placeholder="e.g., Main Warehouse - Bin A5"
                                required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Last Restocked Date</label>
                            <input id="product_last_restocked_date" type="date" name="last_restocked_date"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Last Stock Movement</label>
                            <input id="product_last_stock_movement" type="text" name="last_stock_movement"
                                placeholder="e.g., Stock IN — 120 units on 15 May 2026"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Pricing -->
                <div class="border-b border-slate-200 pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-brand-100 text-brand-600 text-xs font-bold">4</span>
                        Pricing & Profit
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cost per Unit (TZS) <span
                                    class="text-red-500">*</span></label>
                            <input id="product_cost_per_unit" type="number" step="0.01" name="cost_per_unit" placeholder="0.00"
                                min="0" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Unit Selling Price (TZS) <span
                                    class="text-red-500">*</span></label>
                            <input id="product_selling_price" type="number" step="0.01" name="selling_price" placeholder="0.00"
                                min="0" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tax / VAT</label>
                            <div class="flex gap-2">
                                <select id="product_tax_vat" name="tax_vat"
                                    class="flex-1 px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                    <option value="18%">18% VAT</option>
                                    <option value="0%">No VAT</option>
                                    <option value="Exempt">Tax Exempt</option>
                                    <option value="custom">Custom</option>
                                </select>
                                <div id="tax-custom-wrap" class="hidden">
                                    <input id="product_tax_vat_custom" type="number" name="tax_vat_custom" step="0.01" min="0"
                                        max="100" placeholder="%"
                                        class="px-3 py-2 border border-slate-300 rounded-md w-24">
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Choose a preset or select Custom to type a
                                percentage.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Profit Margin %</label>
                            <input id="product_profit_margin" type="text" name="profit_margin" placeholder="Auto-calculated" disabled
                                class="w-full px-3 py-2 border border-slate-300 rounded-md bg-slate-50 text-slate-600">
                            <p class="text-xs text-slate-500 mt-1">Auto-calculated on save</p>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Supplier & Expiry -->
                <div class="pb-4">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-brand-100 text-brand-600 text-xs font-bold">5</span>
                        Supplier & Expiry
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Supplier <span
                                    class="text-red-500">*</span></label>
                            <select id="product_supplier_id" name="supplier_id" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                <option value="">Select Supplier</option>
                                <option value="SUP-001">SUP-001: Bosch Ltd</option>
                                <option value="SUP-002">SUP-002: Unga Limited</option>
                                <option value="SUP-003">SUP-003: Yara Tanzania</option>
                                <option value="SUP-004">SUP-004: Regional Distributor</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Expiry Date</label>
                            <input id="product_expiry_date" type="date" name="expiry_date"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                            <p class="text-xs text-slate-500 mt-1">Leave blank for no expiry</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Product Description</label>
                        <textarea id="product_product_description" name="product_description" placeholder="Enter detailed product description..." rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-none"></textarea>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div
                class="sticky bottom-0 bg-slate-50 border-t border-slate-200 px-6 py-4 flex justify-between items-center rounded-b-lg">
                <p class="text-xs text-slate-500"><span class="text-red-500">*</span> = Required fields</p>
                <div class="flex justify-end gap-3 items-center">
                    <button type="button" onclick="closeLocalModal('modalAddProduct')"
                        class="px-4 py-2 border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <div class="flex items-center gap-3" id="product-save-wrap">
                        <button type="submit" id="submitProductBtn"
                            class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md transition-colors font-medium flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Save Product
                        </button>
                    </div>
                </div>
            </div>
            @include('components.loading', [
                'id' => 'productSaveLoading',
                'show' => false,
                'fullPage' => true,
            ])
        </form>
    </div>
</div>
