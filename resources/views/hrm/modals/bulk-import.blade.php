<div id="bulkImportModal" class="hidden fixed inset-0 bg-slate-900 bg-opacity-40 z-50 flex items-start justify-center pt-20 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200 w-full max-w-2xl mx-4 p-6">
        <h2 class="text-lg font-semibold font-display mb-2">Bulk Import Employees</h2>
        <p class="text-sm text-slate-600 mb-4">Upload a CSV file with columns: Name, Email, Phone Number, Department, Join Date (YYYY-MM-DD)</p>

        <div id="importStep1" class="space-y-4">
            <div class="bg-slate-50 border-2 border-dashed border-slate-300 rounded-lg p-6 text-center cursor-pointer" id="dragDropZone">
                <input type="file" id="csvFileInput" accept=".csv,.txt" class="hidden" onchange="handleFileSelect(event)">
                <p id="dragDropPrompt" class="text-sm text-slate-600">Drag CSV file here or <span class="text-brand-600 font-medium">click to browse</span></p>
                <p id="selectedCsvFile" class="hidden text-sm text-brand-600 font-medium"></p>
                <p class="text-xs text-slate-500 mt-2">CSV format: Name, Email, Phone, Department, Join Date</p>
            </div>

            <div id="importStatus" class="hidden rounded-md border px-3 py-2 text-sm"></div>

            @if ($isQualifiedUser)
                <select id="bulkImportCompany" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:border-brand-600 focus:outline-none focus:ring-1 focus:ring-brand-600" required>
                    <option value="">Select Company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" @selected((string) $activeCompanyId === (string) $company->id)>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            @else
                <input type="hidden" id="bulkImportCompany" value="{{ auth()->user()?->company_id }}">
                <div class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm bg-slate-50 text-slate-600">
                    {{ auth()->user()?->company?->name ?? 'No company assigned' }}
                </div>
            @endif

            <button type="button" id="previewBtn" onclick="previewImport()" disabled class="w-full px-4 py-2 bg-brand-600 hover:bg-brand-700 disabled:bg-slate-300 text-white rounded-md text-sm font-medium transition-colors">Preview & Validate</button>
        </div>

        <div id="importStep2" class="hidden space-y-4">
            <div id="previewResults" class="space-y-3">
                <div id="validRowsPreview" class="hidden">
                    <h3 class="text-sm font-medium text-slate-700 mb-2"> Valid Rows (<span id="validCount">0</span>)</h3>
                    <div id="validRowsList" class="bg-slate-50 rounded p-3 max-h-40 overflow-y-auto text-xs text-slate-600 space-y-1"></div>
                </div>

                <div id="errorRowsPreview" class="hidden">
                    <h3 class="text-sm font-medium text-slate-700 mb-2"> Error Rows (<span id="errorCount">0</span>)</h3>
                    <div id="errorRowsList" class="bg-red-50 border border-red-200 rounded p-3 max-h-40 overflow-y-auto text-xs text-red-600 space-y-1"></div>
                </div>

                <div id="previewMessage" class="text-sm text-slate-600 bg-blue-50 border border-blue-200 p-3 rounded"></div>
            </div>

            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="backToUpload()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Back</button>
                <button type="button" id="confirmBtn" onclick="confirmImport()" disabled class="px-4 py-2 bg-brand-600 hover:bg-brand-700 disabled:bg-slate-300 text-white rounded-md text-sm font-medium">Confirm Import</button>
            </div>
        </div>

        <div id="importStep3" class="hidden text-center space-y-4">
            <div id="importResult" class="bg-slate-50 rounded p-4 text-sm"></div>
            <button type="button" onclick="closeBulkImportModal()" class="w-full px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-sm">Close</button>
        </div>

        <div class="flex gap-3 justify-end pt-4 border-t border-slate-200 mt-6">
            <button type="button" onclick="closeBulkImportModal()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 rounded-md text-sm">Cancel</button>
        </div>

        <x-loading id="importLoader" size="lg" color="amber" full-page="true" message="Processing..." :show="false" />
    </div>
</div>
