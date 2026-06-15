<?php

namespace App\Services\Finance\BalanceSheet;

use App\Models\CreateLiability;
use App\Models\Expense;
use App\Models\Salary;

class CurrentLiabilitiesService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    //function to display the current liabilities all the current liabilities from the liabilities table where the term is short term and the due date is less than or equal to now and the current amount is greater than 0
    public function getCurrentLiabilities()
    {
        //get the current liabilities from the liabilities table
        $getShortTermLoans = $this->getShortTermLoans();
        $getAccruedExpenses = $this->getAccruedExpenses();
        $getInterestPayables = $this->getInterestPayables();
        $getSalaries = $this->getSalaries();
        $getPayableVAT = $this->getPayableVAT();

        return [
            'short_term_loans' => $getShortTermLoans,
            'accrued_expenses' => $getAccruedExpenses,
            'interest_payables' => $getInterestPayables,
            'salaries' => $getSalaries,
            'payable_vat' => $getPayableVAT,
        ];
    }


    //function to get the salaries and wages for the balance sheet report from the salaries table
    protected function getSalaries()
    {
        //get the salaries from the salaries table
        $salaries = Salary::where('effective_date', '<=', now())
            ->get()
            ->map(function ($salary) {
                return [
                    'name' => $salary->employee_id,
                    'amount' => $salary->amount,
                ];
            });

        //return the salaries
        return $salaries;
    }

    //get the Payable VAT from the expenses table
    protected function getPayableVAT()
    {

        //get the payable VAT from the expenses table
        //get the expenses where vat_included is true and the amount is greater than 0
        $payableVAT = Expense::where('vat_included', true)
            ->where('amount', '>', 0)
            ->get()
            ->map(function ($expense) {
                return [
                    'name' => $expense->expense_number,
                    'amount' => $expense->vat_amount,
                ];
            });

        return $payableVAT;
    }

    //get the short Term Loans from the Liabilities table
    protected function getShortTermLoans()
    {
        //get the short term loans from the liabilities table
        $shortTermLoans = CreateLiability::where('term', 'Short-term')
            ->whereHas('category', function ($query) {
                $query->where('name', 'Loans & Borrowings');
            })
            ->where('due_date', '<=', now())
            ->where('current_amount', '>', 0)
            ->get()
            ->map(function ($loan) {
                return [
                    'name' => $loan->name,
                    'amount' => $loan->current_amount,
                ];
            });

        return $shortTermLoans;
    }

    //get the accured expenses from the liabilities table
    protected function getAccruedExpenses()
    {
        //get the accured expenses from the liabilities table
        $accruedExpenses = CreateLiability::whereHas('category', function ($query) {
                $query->where('name', 'Accrued Expenses');
            })
            ->where('due_date', '<=', now())
            ->where('current_amount', '>', 0)
            ->get()
            ->map(function ($liability) {
                return [
                    'name' => $liability->name,
                    'amount' => $liability->current_amount,
                ];
            });

        return $accruedExpenses;

        
    }

    //get the interest payables as well here
    protected function getInterestPayables()
    {
        //get the interest payables from the liabilities table
        $interestPayables = CreateLiability::whereHas('category', function ($query) {
            $query->where('name', 'Interest Payables');
        })
            ->where('due_date', '<=', now())
            ->where('current_amount', '>', 0)
            ->get()
            ->map(function ($liability) {
                return [
                    'name' => $liability->name,
                    'amount' => $liability->current_amount,
                ];
            });

        return $interestPayables;
    }

}
