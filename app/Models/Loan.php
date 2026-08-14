<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'bank_id', // Added foreign key for bank/virtual account
        'code',
        'lender',
        'principal',
        'interest_rate',
        'interest_type',
        'term_months',
        'disbursement_date',
        'start_date',
        'maturity_date',
        'outstanding_balance',
        'total_interest_payable',
        'total_repayable',
        'status',
        'purpose',
        'collateral',
        'guarantor',
        'approved_by_id',
        'notes',
    ];

    protected $casts = [
        'principal' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'term_months' => 'integer',
        'disbursement_date' => 'date',
        'start_date' => 'date',
        'maturity_date' => 'date',
        'outstanding_balance' => 'decimal:2',
        'total_interest_payable' => 'decimal:2',
        'total_repayable' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship to the virtual account/bank receiving the loan funds.
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(VirtualAccounts::class, 'bank_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function repaymentSchedule(): HasMany
    {
        return $this->hasMany(LoanRepaymentSchedule::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Generates the next loan code for the current year, e.g. LN-2026-001,
     * LN-2026-002, and so on. Scoped by year so the sequence resets every
     * January instead of growing forever. Called from LoanController::store()
     * — the person filling in the Add Loan form never types this in.
     */
    public static function generateNextCode(): string
    {
        $year = now()->year;
        $prefix = "LN-{$year}-";

        // Numeric max so LN-2026-010 is not sorted before LN-2026-009 as a string.
        $max = static::withTrashed()
            ->where('code', 'like', "{$prefix}%")
            ->get(['code'])
            ->map(fn ($loan) => (int) substr((string) $loan->code, strlen($prefix)))
            ->max();

        $nextNumber = ((int) $max) + 1;

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Build the full installment schedule from this loan's own terms and
     * persist it. Wipes any existing schedule rows first, so call this
     * once when the loan is created (or again if terms are corrected
     * before the first payment is made).
     *
     * Supports two interest types:
     *  - "Flat": interest is charged on the original principal for every
     *    installment, so each installment is identical in size.
     *  - "Reducing Balance": interest is charged only on the remaining
     *    principal, so the interest portion shrinks each installment.
     */
    public function generateSchedule(): void
    {
        $this->repaymentSchedule()->delete();

        $months = $this->term_months;
        $monthlyRate = ($this->interest_rate / 100) / 12;
        $dueDate = $this->start_date->copy();

        $totalInterest = 0;
        $totalRepayable = 0;

        if ($this->interest_type === 'Flat') {
            $flatInterestPerMonth = $this->principal * $monthlyRate;
            $principalPerMonth = round($this->principal / $months, 2);

            for ($i = 1; $i <= $months; $i++) {
                $dueDate = $dueDate->copy()->addMonthNoOverflow();
                $principalPortion = $i === $months
                    ? $this->principal - ($principalPerMonth * ($months - 1))
                    : $principalPerMonth;
                $interestPortion = round($flatInterestPerMonth, 2);
                $installmentTotal = round($principalPortion + $interestPortion, 2);

                $this->repaymentSchedule()->create([
                    'installment_number' => $i,
                    'due_date' => $dueDate,
                    'principal_portion' => $principalPortion,
                    'interest_portion' => $interestPortion,
                    'total_installment' => $installmentTotal,
                    'status' => 'Pending',
                ]);

                $totalInterest += $interestPortion;
                $totalRepayable += $installmentTotal;
            }
        } else { // Reducing Balance
            $balance = (float) $this->principal;
            // Standard amortized installment formula.
            $installmentTotal = $monthlyRate > 0
                ? round(($balance * $monthlyRate) / (1 - (1 + $monthlyRate) ** -$months), 2)
                : round($balance / $months, 2);

            for ($i = 1; $i <= $months; $i++) {
                $dueDate = $dueDate->copy()->addMonthNoOverflow();
                $interestPortion = round($balance * $monthlyRate, 2);
                $principalPortion = $i === $months
                    ? round($balance, 2)
                    : round($installmentTotal - $interestPortion, 2);

                $balance = round($balance - $principalPortion, 2);

                $this->repaymentSchedule()->create([
                    'installment_number' => $i,
                    'due_date' => $dueDate,
                    'principal_portion' => $principalPortion,
                    'interest_portion' => $interestPortion,
                    'total_installment' => round($principalPortion + $interestPortion, 2),
                    'status' => 'Pending',
                ]);

                $totalInterest += $interestPortion;
                $totalRepayable += $principalPortion + $interestPortion;
            }
        }

        $this->update([
            'outstanding_balance' => $this->principal,
            'total_interest_payable' => round($totalInterest, 2),
            'total_repayable' => round($totalRepayable, 2),
        ]);
    }
    
}