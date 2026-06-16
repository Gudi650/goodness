<?php

namespace App\Http\Controllers;

use App\Models\CreateLiability;
use App\Models\Expense;
use App\Models\Salary;
use App\Services\Finance\BalanceSheet\CurrentAssetsService;
use App\Services\Finance\BalanceSheet\CurrentLiabilitiesService;
use App\Services\Finance\BalanceSheet\NonCurrentAssetsService;
use App\Services\Finance\BalanceSheet\NonCurrentLiabilitiesService;
use Barryvdh\DomPDF\Facade\Pdf;


class balanceSheetController extends Controller
{
    private function reportData(): array
    {
        /*
         * Replace these arrays with database queries
        
        
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
        ]; */

        $equityLiabilities = [

            'equity' => [
                ['name' => 'Share Capital', 'amount' => 400000],
                ['name' => 'Retained Earnings', 'amount' => 100000],
                ['name' => 'Other Equity', 'amount' => 15000],
            ]

        ];
        
        /*
        $totalNonCurrentAssets = collect($assets['non_current'])->sum('amount');
        $totalCurrentAssets = collect($assets['current'])->sum('amount'); 
        $totalAssets = $totalNonCurrentAssets + $totalCurrentAssets; */
        $totalEquity = collect($equityLiabilities['equity'])->sum('amount');
        //$totalNonCurrentLiabilities = collect($equityLiabilities['non_current_liabilities'])->sum('amount');
        //$totalCurrentLiabilities = collect($equityLiabilities['current_liabilities'])->sum('amount');
        //$totalEquityAndLiabilities = $totalEquity + $totalNonCurrentLiabilities + $totalCurrentLiabilities;



        //get the Non current liabilities data from service file
        $nonCurrentLiabilities = app(NonCurrentLiabilitiesService::class)->getNonCurrentLiabilities();

        //get the current liabilities data from service file
        $currentLiabilities = app(CurrentLiabilitiesService::class)->getCurrentLiabilities();

        //get the current assets data from service file
        $currentAssets = app(CurrentAssetsService::class)->getCurrentAssets();

        //get the non current assets data from service file
        $nonCurrentAssets = app(NonCurrentAssetsService::class)->getNonCurrentAssets();



        return [
            /*
            'assets' => $assets,
            
            'totalNonCurrentAssets' => $totalNonCurrentAssets,
            'totalCurrentAssets' => $totalCurrentAssets,
            'totalAssets' => $totalAssets, */
            'totalEquity' => $totalEquity,
            'equityLiabilities' => $equityLiabilities,
            //'totalNonCurrentLiabilities' => $totalNonCurrentLiabilities,
            //'totalCurrentLiabilities' => $totalCurrentLiabilities,
            //'totalEquityAndLiabilities' => $totalEquityAndLiabilities,
            'nonCurrentLiabilities' => $nonCurrentLiabilities,
            'currentLiabilities' => $currentLiabilities,
            'nonCurrentAssets' => $nonCurrentAssets,
            'currentAssets' => $currentAssets,

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

        return $pdf->download('balance_sheet.pdf');
    }


}
