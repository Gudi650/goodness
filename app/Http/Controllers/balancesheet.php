<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;


class balanceSheet extends Controller
{
    private function reportData(): array
    {
        /*
         * Replace these arrays with database queries
         */

        $assets = [
            'non_current' => [
                ['name' => 'Property, Plant & Equipment', 'amount' => 500000],
                ['name' => 'Intangible Assets', 'amount' => 50000],
                ['name' => 'Investments', 'amount' => 30000],
                ['name' => 'Other Non-Current Assets', 'amount' => 20000],
            ],

            'current' => [
                ['name' => 'Inventories', 'amount' => 100000],
                ['name' => 'Investments', 'amount' => 25000],
                ['name' => 'Trade Receivables', 'amount' => 70000],
                ['name' => 'Cash and Cash Equivalents', 'amount' => 55000],
                ['name' => 'Other Current Assets', 'amount' => 10000],
            ]
        ];

        $equityLiabilities = [
            'equity' => [
                ['name' => 'Share Capital', 'amount' => 400000],
                ['name' => 'Retained Earnings', 'amount' => 100000],
                ['name' => 'Other Equity', 'amount' => 15000],
            ],

            'non_current_liabilities' => [
                ['name' => 'Long-Term Borrowings', 'amount' => 150000],
                ['name' => 'Long-Term Provisions', 'amount' => 10000],
                ['name' => 'Deferred Tax Liabilities', 'amount' => 5000],
                ['name' => 'Other Non-Current Liabilities', 'amount' => 5000],
            ],

            'current_liabilities' => [
                ['name' => 'Short-Term Borrowings', 'amount' => 50000],
                ['name' => 'Trade Payables', 'amount' => 80000],
                ['name' => 'Other Current Liabilities', 'amount' => 30000],
                ['name' => 'Short-Term Provisions', 'amount' => 15000],
            ]
        ];

        $totalNonCurrentAssets = collect($assets['non_current'])->sum('amount');
        $totalCurrentAssets = collect($assets['current'])->sum('amount');
        $totalAssets = $totalNonCurrentAssets + $totalCurrentAssets;
        $totalEquity = collect($equityLiabilities['equity'])->sum('amount');
        $totalNonCurrentLiabilities = collect($equityLiabilities['non_current_liabilities'])->sum('amount');
        $totalCurrentLiabilities = collect($equityLiabilities['current_liabilities'])->sum('amount');
        $totalEquityAndLiabilities = $totalEquity + $totalNonCurrentLiabilities + $totalCurrentLiabilities;

        return [
            'assets' => $assets,
            'equityLiabilities' => $equityLiabilities,
            'totalNonCurrentAssets' => $totalNonCurrentAssets,
            'totalCurrentAssets' => $totalCurrentAssets,
            'totalAssets' => $totalAssets,
            'totalEquity' => $totalEquity,
            'totalNonCurrentLiabilities' => $totalNonCurrentLiabilities,
            'totalCurrentLiabilities' => $totalCurrentLiabilities,
            'totalEquityAndLiabilities' => $totalEquityAndLiabilities,
        ];
    }

    public function index()
    {
        return view('reports.balance_sheet', array_merge($this->reportData(), [
            'showActions' => true,
        ]));
    }

    public function exportPdf()
    {
        $pdf = Pdf::loadView('reports.balance_sheet', array_merge($this->reportData(), [
            'showActions' => false,
        ]));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('balance-sheet.pdf');
    }
}