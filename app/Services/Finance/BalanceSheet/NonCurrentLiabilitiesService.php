<?php

namespace App\Services\Finance\BalanceSheet;

use App\Models\CreateLiability;
use App\Models\Expense;
use App\Models\Loan;
use App\Models\Salary;
use App\Support\ReportFilters;

class NonCurrentLiabilitiesService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    //function to display the non current liabilities all the non current liabilities from the liabilities table where the term is long term and the due date is greater than now and the current amount is greater than 0
    public function getNonCurrentLiabilities()
    {
        return [
            'long_term_loans' => $this->getLongTermLoans(),
        ];
    }


    protected function getLongTermLoans()
    {
        $query = CreateLiability::where('term', 'Long-term')
            ->whereHas('category', function ($query) {
                $query->where('category', 'Loans & Borrowings');
            })
            ->where('due_date', '>', now())
            ->where('current_amount', '>', 0);
        ReportFilters::current()->applyCompany($query);
        $fromLiabilities = $query->get()
            ->map(function ($loan) {
                return [
                    'name' => $loan->name,
                    'amount' => $loan->current_amount,
                    'type' => 'cr',
                ];
            });

        $fromModule = Loan::query()
            ->where('is_disbursed', true)
            ->where('outstanding_balance', '>', 0)
            ->whereDate('maturity_date', '>', now()->addYear());
        ReportFilters::current()->applyCompany($fromModule);

        $moduleRows = $fromModule->get()->map(function (Loan $loan) {
            return [
                'name' => trim(($loan->code ? $loan->code.' — ' : '').$loan->lender),
                'amount' => (float) $loan->outstanding_balance,
                'type' => 'cr',
            ];
        });

        return $fromLiabilities->concat($moduleRows)->values();
    }


}
