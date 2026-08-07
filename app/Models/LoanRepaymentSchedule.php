<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanRepaymentSchedule extends Model
{
    use HasFactory;

    protected $table = 'loan_repayment_schedules';

    protected $fillable = [
        'loan_id',
        'installment_number',
        'due_date',
        'principal_portion',
        'interest_portion',
        'total_installment',
        'status',
    ];

    protected $casts = [
        'installment_number' => 'integer',
        'due_date' => 'date',
        'principal_portion' => 'decimal:2',
        'interest_portion' => 'decimal:2',
        'total_installment' => 'decimal:2',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'Pending')->whereDate('due_date', '<', now());
    }
    
}