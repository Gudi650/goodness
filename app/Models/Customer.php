<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'customer_code',
    'company_id',
    'customer_type',
    'customer_name',
    'business_trading_name',
    'registration_number',
    'industry_sector',
    'status',
    'contact_person_name',
    'phone_number',
    'alternative_phone_number',
    'email',
    'whatsapp_number',
    'website',
    'country',
    'region',
    'district',
    'street_address',
    'po_box',
    'assigned_sales_rep_id',
    'customer_source',
    'price_category',
    'credit_limit',
    'payment_terms',
    'preferred_payment_method',
    'currency_preference',
    'bank_name',
    'account_name',
    'account_number',
    'mobile_money_number',
    'date_of_birth',
    'gender',
    'national_id_number',
    'tags',
    'customer_rating',
    'notes',
])]
class Customer extends Model
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function assignedSalesRep(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_sales_rep_id');
    }

    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:2',
            'date_of_birth' => 'date',
            'customer_rating' => 'integer',
        ];
    }
}