<?php

namespace App\Services\Finance\BalanceSheet;

use App\Models\CreateLiability;
use App\Models\Expense;
use App\Models\Invoice;
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
        //$getSalaries = $this->getSalaries();
        $getPayableVAT = $this->getPayableVAT();

        return [
            'short_term_loans' => $getShortTermLoans,
            'accrued_expenses' => $getAccruedExpenses,
            'interest_payables' => $getInterestPayables,
            //'salaries' => $getSalaries,
            'payable_vat' => $getPayableVAT,
        ];
    }


    //function to get the salaries and wages for the balance sheet report from the salaries table
    protected function getSalaries()
    {
        //$salaries = Expense::where('category', 'Operating Expenses')
        $salaries = Expense::where('status', 'issued')
            ->whereHas('financeItem', function ($query) {
                $query->where('item_name', 'Salaries and Wages');
            })
            //->where('status', 'issued')
            ->get()
            ->map(function ($salary) {
                return [
                    'name' => $salary->expense_number,
                    'amount' => $salary->amount,
                    'type' => 'dr', // Assuming liabilities are credit entries
                ];
            });
        
        //dd($salaries);

        //return the salaries
        return $salaries;
    }

    //get the Payable VAT from the expenses table
    protected function getPayableVAT()
    {

        //get the payable VAT from the expenses table
        //get the expenses where vat_included is true and the amount is greater than 0
        $ExpensesVAT = Expense::where('vat_included', true)
            ->where('status', 'issued')
            ->where('amount', '>', 0)
            ->get()
            ->map(function ($expense) {
                return [
                    'name' => $expense->expense_number,
                    'amount' => $expense->vat_amount,
                    'type' => 'cr', // Assuming liabilities are credit entries
                ];
            });

        //get the invoice vat 
        $invoiceVAT = Invoice::where('status', 'paid')
            ->where('tax_amount','>', 0)
            ->get()
            ->map(function ($invoice) {
                return [
                    'name' => $invoice->invoice_number,
                    'amount' => $invoice->tax_amount,
                    'type' => 'cr', // Assuming liabilities are credit entries
                ];
            });

        //dd($invoiceVAT);

        //payable vat is difference btn the invoice vat and the expense vat
        $payableVATAmount = $invoiceVAT->sum('amount') - $ExpensesVAT->sum('amount');


        //now check if the payable vat is less than 0 then return 0
        if ($payableVATAmount < 0) {
            $name = 'Receivable VAT';
            $type = 'dr';
            $payableVATAmount = abs($payableVATAmount);
        } else {
            $name= 'Payable VAT';
            $type = 'cr';
        } 
        

        return collect([
            [
                'name' => $name,
                'amount' => $payableVATAmount,
                'type' => $type,
            ],
        ]);
        
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
                    'type' => 'cr', // Assuming liabilities are credit entries
                ];
            });

        return $shortTermLoans;
    }

    //get the accured expenses from the liabilities table
    protected function getAccruedExpenses()
    {
        //get the accured expenses from the liabilities table
        $accruedExpenses = CreateLiability::whereHas('category', function ($query) {
                $query->where('category', 'Accrued Expenses');
            })
            ->where('due_date', '<=', now())
            ->where('current_amount', '>', 0)
            ->get()
            ->map(function ($liability) {
                return [
                    'name' => $liability->name,
                    'amount' => $liability->current_amount,
                    'type' => 'cr',
                ];
            });

        return $accruedExpenses;

        
    }

    //get the interest payables as well here
    protected function getInterestPayables()
    {
        //get the interest payables from the liabilities table
        $interestPayables = CreateLiability::whereHas('category', function ($query) {
            $query->where('category', 'Interest Payables');
        })
            ->where('due_date', '<=', now())
            ->where('current_amount', '>', 0)
            ->get()
            ->map(function ($liability) {
                return [
                    'name' => $liability->name,
                    'amount' => $liability->current_amount,
                    'type' => 'cr', // Assuming liabilities are credit entries
                ];
            });

        return $interestPayables;
    }

}
