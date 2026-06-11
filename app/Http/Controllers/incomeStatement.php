<?php

namespace App\Http\Controllers;


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

            'revenue' => [
                ['name' => 'Sales revenue', 'amount' => 120000],
                ['name' => 'Service income', 'amount' => 30000],
            ],

            'cogs' => [
                ['name' => 'Materials', 'amount' => 40000],
                ['name' => 'Labor', 'amount' => 20000],
            ],

            'operating_expenses' => [
                ['name' => 'Salaries and wages', 'amount' => 20000],
                ['name' => 'Marketing and advertising', 'amount' => 10000],
                ['name' => 'Office rent', 'amount' => 5000],
            ],

            'other_items' => [
                ['name' => 'Interest income', 'amount' => 1000],
                ['name' => 'Legal settlement loss', 'amount' => -2000],
            ],

            'tax_expense' => 13500,
        ];

        $totalRevenue = collect($data['revenue'])->sum('amount');
        $totalCogs = collect($data['cogs'])->sum('amount');
        $grossProfit = $totalRevenue - $totalCogs;
        $totalOperatingExpenses = collect($data['operating_expenses'])->sum('amount');
        $operatingIncome = $grossProfit - $totalOperatingExpenses;
        $otherItemsTotal = collect($data['other_items'])->sum('amount');
        $preTaxIncome = $operatingIncome + $otherItemsTotal;
        $netIncome = $preTaxIncome - $data['tax_expense'];

        return [
            'data' => $data,
            'totalRevenue' => $totalRevenue,
            'totalCogs' => $totalCogs,
            'grossProfit' => $grossProfit,
            'totalOperatingExpenses' => $totalOperatingExpenses,
            'operatingIncome' => $operatingIncome,
            'otherItemsTotal' => $otherItemsTotal,
            'preTaxIncome' => $preTaxIncome,
            'netIncome' => $netIncome,
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
}