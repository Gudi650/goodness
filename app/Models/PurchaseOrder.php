<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'po_number',
        'po_date',
        'expected_delivery_date',
        'company_id',
        'department_id',
        'priority_level',
        'status',
        'supplier_id',
        'delivery_address',
        'delivery_method',
        'subtotal',
        'discount_percent',
        'discount_amount',
        'vat_percent',
        'vat_amount',
        'shipping_cost',
        'total_amount',
        'payment_terms',
        'payment_method',
        'deposit_amount',
        'balance_due',
        'requested_by',
        'approved_by',
        'approval_date',
        'authorization_notes',
        'supporting_document_path',
        'supporting_document_name',
        'internal_notes',
        'terms_and_conditions',
    ];

    protected $casts = [
        'po_date' => 'date',
        'expected_delivery_date' => 'date',
        'approval_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'vat_percent' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
