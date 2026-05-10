<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'customer_type' => 'required|string|max:50',
            'customer_name' => 'required|string|max:255',
            'business_trading_name' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'industry_sector' => 'nullable|string|max:100',
            'status' => 'required|in:Active,Inactive,Blacklisted,Prospect',
            'contact_person_name' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:30',
            'alternative_phone_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'whatsapp_number' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'street_address' => 'nullable|string|max:255',
            'po_box' => 'nullable|string|max:100',
            'assigned_sales_rep_id' => 'nullable|exists:users,id',
            'customer_source' => 'nullable|string|max:100',
            'price_category' => 'nullable|string|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string|max:50',
            'preferred_payment_method' => 'nullable|string|max:100',
            'currency_preference' => 'nullable|string|max:10',
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'mobile_money_number' => 'nullable|string|max:30',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Prefer not to say',
            'national_id_number' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
            'customer_rating' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $customerCode = 'CUST-' . str_pad((string) ((Customer::max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT);

        Customer::create([
            'customer_code' => $customerCode,
            'company_id' => $validated['company_id'] ?? null,
            'customer_type' => $validated['customer_type'],
            'customer_name' => $validated['customer_name'],
            'business_trading_name' => $validated['business_trading_name'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'industry_sector' => $validated['industry_sector'] ?? null,
            'status' => $validated['status'],
            'contact_person_name' => $validated['contact_person_name'] ?? null,
            'phone_number' => $validated['phone_number'],
            'alternative_phone_number' => $validated['alternative_phone_number'] ?? null,
            'email' => $validated['email'] ?? null,
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'website' => $validated['website'] ?? null,
            'country' => $validated['country'] ?? 'Tanzania',
            'region' => $validated['region'] ?? null,
            'district' => $validated['district'] ?? null,
            'street_address' => $validated['street_address'] ?? null,
            'po_box' => $validated['po_box'] ?? null,
            'assigned_sales_rep_id' => $validated['assigned_sales_rep_id'] ?? null,
            'customer_source' => $validated['customer_source'] ?? null,
            'price_category' => $validated['price_category'] ?? null,
            'credit_limit' => $validated['credit_limit'] ?? null,
            'payment_terms' => $validated['payment_terms'] ?? null,
            'preferred_payment_method' => $validated['preferred_payment_method'] ?? null,
            'currency_preference' => $validated['currency_preference'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'account_name' => $validated['account_name'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'mobile_money_number' => $validated['mobile_money_number'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'national_id_number' => $validated['national_id_number'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'customer_rating' => $validated['customer_rating'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'customer_type' => 'required|string|max:50',
            'customer_name' => 'required|string|max:255',
            'business_trading_name' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'industry_sector' => 'nullable|string|max:100',
            'status' => 'required|in:Active,Inactive,Blacklisted,Prospect',
            'contact_person_name' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:30',
            'alternative_phone_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'whatsapp_number' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'street_address' => 'nullable|string|max:255',
            'po_box' => 'nullable|string|max:100',
            'assigned_sales_rep_id' => 'nullable|exists:users,id',
            'customer_source' => 'nullable|string|max:100',
            'price_category' => 'nullable|string|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string|max:50',
            'preferred_payment_method' => 'nullable|string|max:100',
            'currency_preference' => 'nullable|string|max:10',
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'mobile_money_number' => 'nullable|string|max:30',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Prefer not to say',
            'national_id_number' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
            'customer_rating' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->back()->with('success', 'Customer updated successfully.');
    }
}