<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_type' => 'required|string|max:50',
            'registration_number' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'contact_person_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:30',
            'alternative_phone_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'street_address' => 'nullable|string|max:255',
            'po_box' => 'nullable|string|max:100',
            'categories_supplied' => 'nullable|array',
            'categories_supplied.*' => 'nullable|string|max:100',
            'products_supplied' => 'nullable|string',
            'lead_time' => 'nullable|string|max:50',
            'minimum_order_value' => 'nullable|numeric',
            'payment_terms' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'branch_name' => 'nullable|string|max:100',
            'mobile_money_number' => 'nullable|string|max:30',
            'preferred_payment_method' => 'nullable|string|max:50',
            'rating' => 'nullable|integer|min:1|max:5',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'business_registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'tin_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $supplierId = $request->input('supplier_id');
        if (empty($supplierId)) {
            $nextNumber = (Supplier::max('id') ?? 0) + 1;
            $supplierId = 'SUP-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        }

        $businessRegistrationPath = null;
        $businessRegistrationName = null;
        if ($request->hasFile('business_registration_certificate')) {
            $businessRegistrationFile = $request->file('business_registration_certificate');
            $businessRegistrationPath = $businessRegistrationFile->store('suppliers/documents', 'public');
            $businessRegistrationName = $businessRegistrationFile->getClientOriginalName();
        }

        $tinCertificatePath = null;
        $tinCertificateName = null;
        if ($request->hasFile('tin_certificate')) {
            $tinCertificateFile = $request->file('tin_certificate');
            $tinCertificatePath = $tinCertificateFile->store('suppliers/documents', 'public');
            $tinCertificateName = $tinCertificateFile->getClientOriginalName();
        }

        Supplier::create([
            'supplier_id' => $supplierId,
            'company_id' => $validated['company_id'],
            'supplier_name' => $validated['supplier_name'],
            'supplier_type' => $validated['supplier_type'],
            'registration_number' => $validated['registration_number'] ?? null,
            'status' => $validated['status'] ?? 'Active',
            'contact_person_name' => $validated['contact_person_name'],
            'phone_number' => $validated['phone_number'],
            'alternative_phone_number' => $validated['alternative_phone_number'] ?? null,
            'email' => $validated['email'] ?? null,
            'website' => $validated['website'] ?? null,
            'country' => $validated['country'] ?? 'Tanzania',
            'region' => $validated['region'] ?? null,
            'district' => $validated['district'] ?? null,
            'street_address' => $validated['street_address'] ?? null,
            'po_box' => $validated['po_box'] ?? null,
            'categories_supplied' => isset($validated['categories_supplied']) ? implode(', ', $validated['categories_supplied']) : null,
            'products_supplied' => $validated['products_supplied'] ?? null,
            'lead_time' => $validated['lead_time'] ?? null,
            'minimum_order_value' => $validated['minimum_order_value'] ?? null,
            'payment_terms' => $validated['payment_terms'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'account_name' => $validated['account_name'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'branch_name' => $validated['branch_name'] ?? null,
            'mobile_money_number' => $validated['mobile_money_number'] ?? null,
            'preferred_payment_method' => $validated['preferred_payment_method'] ?? null,
            'rating' => $validated['rating'] ?? null,
            'contract_start_date' => $validated['contract_start_date'] ?? null,
            'contract_end_date' => $validated['contract_end_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'business_registration_certificate_path' => $businessRegistrationPath,
            'business_registration_certificate_name' => $businessRegistrationName,
            'tin_certificate_path' => $tinCertificatePath,
            'tin_certificate_name' => $tinCertificateName,
        ]);

        return redirect()->back()->with('success', 'Supplier created successfully');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_type' => 'required|string|max:50',
            'registration_number' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'contact_person_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:30',
            'alternative_phone_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'street_address' => 'nullable|string|max:255',
            'po_box' => 'nullable|string|max:100',
            'categories_supplied' => 'nullable|array',
            'categories_supplied.*' => 'nullable|string|max:100',
            'products_supplied' => 'nullable|string',
            'lead_time' => 'nullable|string|max:50',
            'minimum_order_value' => 'nullable|numeric',
            'payment_terms' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'branch_name' => 'nullable|string|max:100',
            'mobile_money_number' => 'nullable|string|max:30',
            'preferred_payment_method' => 'nullable|string|max:50',
            'rating' => 'nullable|integer|min:1|max:5',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'business_registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'tin_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle business registration certificate upload
        if ($request->hasFile('business_registration_certificate')) {
            $businessRegistrationFile = $request->file('business_registration_certificate');
            $businessRegistrationPath = $businessRegistrationFile->store('suppliers/documents', 'public');
            $validated['business_registration_certificate_path'] = $businessRegistrationPath;
            $validated['business_registration_certificate_name'] = $businessRegistrationFile->getClientOriginalName();
        }

        // Handle TIN certificate upload
        if ($request->hasFile('tin_certificate')) {
            $tinCertificateFile = $request->file('tin_certificate');
            $tinCertificatePath = $tinCertificateFile->store('suppliers/documents', 'public');
            $validated['tin_certificate_path'] = $tinCertificatePath;
            $validated['tin_certificate_name'] = $tinCertificateFile->getClientOriginalName();
        }

        // Handle categories_supplied array
        if (isset($validated['categories_supplied']) && is_array($validated['categories_supplied'])) {
            $validated['categories_supplied'] = implode(', ', $validated['categories_supplied']);
        }

        // Update the supplier
        $supplier->update($validated);

        return redirect()->back()->with('success', 'Supplier updated successfully');
    }

    //function to downlaod the attachments
    public function downloadAttachment(Supplier $supplier, $type)
    {
        if (!in_array($type, ['business_registration_certificate', 'tin_certificate'])) {
            return redirect()->back()->with('error', 'Invalid document type.');
        }

        $filePath = $supplier->{$type . '_path'};
        $fileName = $supplier->{$type . '_name'};

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($filePath, $fileName);
    }
}
