<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Services\DepreciationValue;
use App\Services\Finance\AssetDisposalService;
use App\Support\ReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;

class IncomeStatement extends Controller
{
    private function reportData(): array
    {
        ReportFilters::boot();

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

        $totalRevenue = $this->getRevenues()->sum('amount');
        

        //get the total revenues by category
        $totalRevenuesByCategory = $this->getTotalRevenuesByCategory();

        //get the total expenses by category
        $totalExpensesByCategory = $this->getExpenseStatement();

        $cogsCategory = $totalExpensesByCategory->keys()->first(function ($key) {
            $k = strtolower((string) $key);
            return str_contains($k, 'cogs') || str_contains($k, 'cost of good sold') || str_contains($k, 'cost of goods sold');
        });

        $excludedCategories = collect([$cogsCategory, 'Investment'])
            ->filter()
            ->values();

        // total expenses excluding COGS and Investment, matching the report layout
        $totalExpenses = $totalExpensesByCategory
            ->reject(function ($items, $category) use ($excludedCategories) {
                return $excludedCategories->contains($category)
                    || str_contains(strtolower((string) $category), 'investment');
            })
            ->flatten()
            ->sum();

        // get the total expenses of COGS category
        $totalCOGS = $cogsCategory ? ($totalExpensesByCategory->get($cogsCategory, collect())->sum() ?? 0) : 0;

        $depreciationValue = $this->getDepreciationValue();

        // expenses shown in the statement
        $totalOperatingExpenses = $totalExpenses + $depreciationValue;

        //gross profit is the difference between total revenue and total COGS
        $grossProfit = $totalRevenue - $totalCOGS;


        $operatingIncome = $grossProfit - $totalOperatingExpenses;

        $otherItemsTotal = app(AssetDisposalService::class)->gainOrLoss();

        $preTaxIncome = $operatingIncome + $otherItemsTotal;

        //tax expense is calculated as a percentage of pre-tax income, for example 30%
        //put a condition if preTaxIncome is zero or neg the taxExpense s zero
        $taxExpense = $preTaxIncome > 0 ? $preTaxIncome * 0.18 : 0;

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
            'depreciationValue' => $depreciationValue,
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
        $query = Invoice::where('status', 'paid');
        ReportFilters::current()->apply($query, 'invoice_date');

        return $query->get()
            ->map(function ($invoice) {
                return [
                    'name'     => $invoice->invoice_number,
                    'category' => $invoice->category,
                    'amount'   => ($invoice->total_amount ?? 0) - ($invoice->tax_amount ?? 0),
                    'type'     => 'cr',
                ];
            });

    }

    //now get the total of all revenues per categories
    protected function getTotalRevenuesByCategory()
    {
        $revenues = $this->getRevenues();

        return $revenues->groupBy('category')->map(function ($group) {
            return $group->sum('amount');
        });
        
    }

    //get depreciation value
    protected function getDepreciationValue()
    {
        return app(DepreciationValue::class)->getDepreciationValue();
    }

    //function to get the expenses from expense table in the database
    //use the expenses which are issued here
    protected function getExpenses()
    {
        $query = Expense::where('status', 'issued');
        ReportFilters::current()->apply($query, 'expense_date');
        $expenses = $query->get();

        if ($expenses->isEmpty()) {
            return collect();
        }

        return $expenses->filter();
    }

    //function to get the total of all expenses per categories
    protected function getExpenseStatement()
    {
        $expenses = $this->getExpenses();

        $expenseStatement = $expenses
            ->groupBy(function ($expense) {
                return $expense?->category ?? 'Uncategorized_Category';
            })
            ->map(function ($categoryExpenses) {

                return $categoryExpenses
                    ->groupBy(function ($expense) {
                        return $expense?->financeItem?->item_name ?? 'Uncategorized_Sub_Category';
                    })
                    ->map(function ($itemExpenses) {
                        return $itemExpenses->sum('amount');
                    });

            });
            

        $orderedExpenseStatement = collect();

        foreach (['Cost of Goods Sold (COGS)', 'Operational'] as $category) {
            if ($expenseStatement->has($category)) {
                $orderedExpenseStatement->put($category, $expenseStatement->get($category));
            }
        }

        foreach ($expenseStatement as $category => $items) {
            if (! $orderedExpenseStatement->has($category)) {
                $orderedExpenseStatement->put($category, $items);
            }
        }

        return $orderedExpenseStatement;
    }

}