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
    'approved_at',
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
    'review_rating',
    'review_feedback',
    'review_items',
    'review_evidence_paths',
    'reviewed_at',
    'bank_id',
    'term',
    'description',
    'sub_category_id',

])]
class Expense extends Model
{
    use HasFactory;

    /**
     * Attribute casts for date and review fields.
     */
    protected $casts = [
        'expense_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'vat_included' => 'boolean',
        'review_items' => 'array',
        'review_evidence_paths' => 'array',
    ];

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

    /**
     * Get the bank account associated with this expense.
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(VirtualAccounts::class, 'bank_id');
    }

    /**
     * Get the finance item associated with this expense.
     */
    public function financeItem(): BelongsTo
    {
        return $this->belongsTo(FinanceItems::class, 'sub_category_id');
    }
}
