<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'order_number',
    'order_date',
    'expected_delivery_date',
    'company_id',
    'department_id',
    'customer_id',
    'billing_address',
    'shipping_address',
    'order_type',
    'priority',
    'status',
    'subtotal',
    'discount_percent',
    'discount_amount',
    'vat_enabled',
    'vat_percent',
    'vat_amount',
    'shipping_cost',
    'other_charges',
    'grand_total',
    'amount_paid',
    'balance_due',
    'payment_status',
    'payment_method',
    'payment_reference',
    'payment_date',
    'credit_terms',
    'credit_due_date',
    'delivery_method',
    'delivery_date',
    'delivery_status',
    'driver_name',
    'vehicle_plate_number',
    'delivery_notes',
    'sales_rep_id',
    'approved_by',
    'commission_percent',
    'commission_amount',
    'lpo_file_path',
    'lpo_file_name',
    'internal_notes',
    'customer_notes',
    'terms_and_conditions',
])]
class Order extends Model
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesRep(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'expected_delivery_date' => 'date',
            'payment_date' => 'date',
            'credit_due_date' => 'date',
            'delivery_date' => 'date',
            'vat_enabled' => 'boolean',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'other_charges' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'balance_due' => 'decimal:2',
            'commission_percent' => 'decimal:2',
            'commission_amount' => 'decimal:2',
        ];
    }
}
