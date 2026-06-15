<?php

namespace App\Services\Finance\BalanceSheet;

use App\Models\CreateLiability;
use App\Models\Expense;
use App\Models\Salary;

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
        //get the non current liabilities from the liabilities table
        $getLongTermLoans = $this->getLongTermLoans();

        //return the non current liabilities
        return $getLongTermLoans;
    }


    //function to get the long tem loans from the liabilities table as well here
    protected function getLongTermLoans()
    {
        //get the long term loans from the liabilities table
        $longTermLoans = CreateLiability::where('term', 'Long-term')
            ->whereHas('category', function ($query) {
                $query->where('category', 'Loans & Borrowings');
            })
            ->where('due_date', '>', now())
            ->where('current_amount', '>', 0)
            ->get()
            ->map(function ($loan) {
                return [
                    'name' => $loan->name,
                    'amount' => $loan->current_amount,
                ];
            });

        return $longTermLoans;

        
    }


}
