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

        // expenses shown in the statement
        $totalOperatingExpenses = $totalExpenses;

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

        //$revenues = Invoice::where('status', 'draft')->get();

        //fetch revenues from the database
        $Totalrevenues = Invoice::where('status', 'paid')->get();

        //get the VAT amount from the invoices and subtract it from the total amount to get the revenue
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
        
        //sum of the VAT amount from the invoices
        $totalInvoiceVAT = $invoiceVAT->sum('amount');

        dd($totalInvoiceVAT);

        //subtract the VAT amount from the total amount to get the revenue
        $revenues = $Totalrevenues->map(function ($invoice) use ($totalInvoiceVAT) {
            return [
                'name' => $invoice->invoice_number,
                'amount' => $invoice->total_amount - $totalInvoiceVAT,
                'type' => 'cr', // Assuming liabilities are credit entries
            ];
        });
    
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