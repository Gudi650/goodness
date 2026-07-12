<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class IncomeStatement extends Controller
{
    private function reportData(): array
    {
        /*
         * Replace these with values from your database
         */

        $data = [
            'period' => 'Q1 2025',

            'other_items' => [
                ['name' => 'Interest income', 'amount' => 0],
                ['name' => 'Legal settlement loss', 'amount' => -0],
            ],

            'tax_expense' => 0,
        ];

        $totalRevenue = $this->getRevenues()->sum('total_amount');
        

        //get the total revenues by category
        $totalRevenuesByCategory = $this->getTotalRevenuesByCategory();

        //get the total expenses by category
        $totalExpensesByCategory = $this->getExpenseStatement();

        //total expenses of all categories
        $totalExpenses = collect($totalExpensesByCategory)
        ->flatten()
        ->sum();

        //get the total expenses of COGS category
        $totalCOGS = $totalExpensesByCategory->get('Cost of Goods Sold (COGS)', collect())->sum() ?? 0;

        //get the total of operating expenses category
        $totalOperatingExpenses = $totalExpensesByCategory->get('Operational', collect())->sum() ?? 0;

        //gross profit is the difference between total revenue and total COGS
        $grossProfit = $totalRevenue - $totalCOGS;


        $operatingIncome = $grossProfit - $totalOperatingExpenses;

        $otherItemsTotal = 0;

        $preTaxIncome = $operatingIncome + $otherItemsTotal;

        //tax expense is calculated as a percentage of pre-tax income, for example 30%
        $taxExpense = $preTaxIncome * 0.18;

        $netIncome = $preTaxIncome - $taxExpense;

        return [
            'data' => $data,
            'totalRevenue' => $totalRevenue,
            'grossProfit' => $grossProfit,
            'totalOperatingExpenses' => $totalOperatingExpenses,
            'operatingIncome' => $operatingIncome,
            'otherItemsTotal' => $otherItemsTotal,
            'preTaxIncome' => $preTaxIncome,
            'netIncome' => $netIncome,
            'totalRevenuesByCategory' => $totalRevenuesByCategory,
            'totalExpensesByCategory' => $totalExpensesByCategory,
            'totalExpenses' => $totalExpenses,
            'taxExpense' => $taxExpense,
        ];
    }

    public function index()
    {
        return view('reports.income_statement', array_merge($this->reportData(), [
            'showActions' => true,
        ]));
    }

    public function exportPdf()
    {
        $pdf = Pdf::loadView('reports.income_statement', array_merge($this->reportData(), [
            'showActions' => false,
        ]));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('income-statement.pdf');
    }

    //function to get the revenues from invoices table in the database
    //use the invoices which are paid here
    protected function getRevenues()
    {
        //fetch revenues from the database
        $revenues = Invoice::where('status', 'draft')->get();
    
        return $revenues;
        
    }

    //now get the total of all revenues per categories
    protected function getTotalRevenuesByCategory()
    {
        $revenues = $this->getRevenues();
    
        $totalIncomeByCategory = $revenues->groupBy('category')->map(function ($group) {
            return $group->sum('total_amount');
        });
    
        return $totalIncomeByCategory;
    }

    //function to get the expenses from expense table in the database
    //use the expenses which are issued here
    protected function getExpenses()
    {
        //fetch expenses from the database
        $expenses = Expense::where('status', 'issued')->get();

        //if empty return an empty collection
        if ($expenses->isEmpty()) {
            return collect();
        }

        return $expenses->filter();
    }

    //function to get the total of all expenses per categories
    protected function getExpenseStatement()
    {
        $expenses = $this->getExpenses();

        return $expenses
            ->groupBy(function ($expense) {
                return $expense?->category ?? 'Uncategorized';
            })
            ->map(function ($categoryExpenses) {

                return $categoryExpenses
                    ->groupBy(function ($expense) {
                        return $expense?->financeItem?->item_name ?? 'Uncategorized';
                    })
                    ->map(function ($itemExpenses) {
                        return $itemExpenses->sum('amount');
                    });

            });
    }

}