<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'invoice_number',
    'company_id',
    'client_name',
    'client_email',
    'client_phone',
    'client_address',
    'invoice_date',
    'due_date',
    'status',
    'payment_method',
    'subtotal',
    'tax_amount',
    'discount_amount',
    'total_amount',
    'notes',
    'created_by',
    'invoice_type',
    'bank_id',
    'category',

])]
class Invoice extends Model
{
    use HasFactory;

    /**
     * Get the company this invoice belongs to.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who created this invoice.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the invoice items for this invoice.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    //relationship with the bank
    public function bank(): BelongsTo
    {
        return $this->belongsTo(VirtualAccounts::class);
    }
}
