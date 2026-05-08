<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_reference',
        'payment_date',
        'company',
        'payment_direction',
        'party_name',
        'payment_method',
        'reference_number',
        'payment_category',
        'linked_to',
        'amount',
        'currency',
        'exchange_rate',
        'payment_status',
        'notes',
        'proof_of_payment_path',
        'original_proof_filename',
        'submit_mode',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
    ];

    /**
     * Get the calculated TZS equivalent amount
     */
    public function getTzsEquivalentAttribute(): float
    {
        if ($this->currency === 'TZS') {
            return (float) $this->amount;
        }
        return (float) ($this->amount * $this->exchange_rate);
    }

    /**
     * Get the proof of payment URL if available
     */
    public function getProofUrlAttribute(): ?string
    {
        return $this->proof_of_payment_path ? Storage::disk('public')->url($this->proof_of_payment_path) : null;
    }
}
