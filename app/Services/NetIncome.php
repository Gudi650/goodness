<?php

namespace App\Services;

use App\Models\Dividends;
use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Support\Carbon;

class NetIncome
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    //function to get the net income
    public function calculateNetIncome(?int $companyId = null, ?int $year = null): float
    {
        $totalRevenue = $this->getRevenues($companyId, $year)->sum('total_amount');
        $totalExpenses = $this->getExpenses($companyId, $year)->sum('amount');

        $totalRevenuesByCategory = $this->getTotalRevenuesByCategory($companyId, $year);
        $totalExpensesByCategory = $this->getExpenseStatement($companyId, $year);
        $totalCOGS = $totalExpensesByCategory->get('Cost of Goods Sold (COGS)', collect())->sum() ?? 0;
        $totalOperatingExpenses = $totalExpensesByCategory->get('Operational', collect())->sum() ?? 0;

        //gross profit is the difference between total revenue and total COGS
        $grossProfit = $totalRevenue - $totalCOGS;

        $operatingIncome = $grossProfit - $totalOperatingExpenses;

        $otherItemsTotal = 0;

        $preTaxIncome = $operatingIncome + $otherItemsTotal;

        //tax expense is calculated as a percentage of pre-tax income, for example 30%
        $taxExpense = $preTaxIncome * 0.18;

        $netIncome = $preTaxIncome - $taxExpense;

        return $netIncome;

    }


    //function to get the revenues from invoices table in the database
    //use the invoices which are paid here
    protected function getRevenues(?int $companyId = null, ?int $year = null)
    {
        //fetch revenues from the database
        $revenues = Invoice::query()->where('status', 'draft');

        if ($companyId) {
            $revenues->where('company_id', $companyId);
        }

        if ($year) {
            $revenues->whereYear('created_at', $year);
        }

        $revenues = $revenues->get();
    
        return $revenues;
        
    }

    //now get the total of all revenues per categories
    protected function getTotalRevenuesByCategory(?int $companyId = null, ?int $year = null)
    {
        $revenues = $this->getRevenues($companyId, $year);
    
        $totalIncomeByCategory = $revenues->groupBy('category')->map(function ($group) {
            return $group->sum('total_amount');
        });
    
        return $totalIncomeByCategory;
    }

    //function to get the expenses from expense table in the database
    //use the expenses which are issued here
    protected function getExpenses(?int $companyId = null, ?int $year = null)
    {
        //fetch expenses from the database
        $expenses = Expense::query()->where('status', 'issued');

        if ($companyId) {
            $expenses->where('company_id', $companyId);
        }

        if ($year) {
            $expenses->whereYear('created_at', $year);
        }

        $expenses = $expenses->get();

        //if empty return an empty collection
        if ($expenses->isEmpty()) {
            return collect();
        }

        return $expenses;
    }

    //function to get the total of all expenses per categories
    protected function getExpenseStatement(?int $companyId = null, ?int $year = null)
    {
        $expenses = $this->getExpenses($companyId, $year);

        return $expenses
            ->groupBy(function ($expense) {
                return $expense->category;
            })
            ->map(function ($categoryExpenses) {

                return $categoryExpenses
                    ->groupBy(function ($expense) {
                        return $expense->financeItem->item_name ?? 'Uncategorized';
                    })
                    ->map(function ($itemExpenses) {
                        return $itemExpenses->sum('amount');
                    });

            });
    }


}
