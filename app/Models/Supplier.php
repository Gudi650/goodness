<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'company_id',
        'supplier_name',
        'supplier_type',
        'registration_number',
        'status',
        'contact_person_name',
        'phone_number',
        'alternative_phone_number',
        'email',
        'website',
        'country',
        'region',
        'district',
        'street_address',
        'po_box',
        'categories_supplied',
        'products_supplied',
        'lead_time',
        'minimum_order_value',
        'payment_terms',
        'bank_name',
        'account_name',
        'account_number',
        'branch_name',
        'mobile_money_number',
        'preferred_payment_method',
        'rating',
        'contract_start_date',
        'contract_end_date',
        'notes',
        'business_registration_certificate_path',
        'business_registration_certificate_name',
        'tin_certificate_path',
        'tin_certificate_name',
    ];

    protected $casts = [
        'minimum_order_value' => 'decimal:2',
        'rating' => 'integer',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
