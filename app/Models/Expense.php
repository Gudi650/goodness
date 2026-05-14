<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'expense_number',
    'company_id',
    'department_id',
    'created_by',
    'approved_by',
    'issued_by',
    'checked_by',
    'status',
    'expense_date',
    'category',
    'sub_category',
    'payment_method',
    'reference_number',
    'amount',
    'vat_included',
    'vat_rate',
    'vat_amount',
    'net_amount',
    'attachment_path',
    'notes',
    'submitted_at',
    'original_file_name',
    ''
])]
class Expense extends Model
{
    use HasFactory;

    /**
     * Get the company this expense belongs to.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the department this expense belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who created the expense.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved the expense.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who issued the expense.
     */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Get the user who checked the expense.
     */
    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
