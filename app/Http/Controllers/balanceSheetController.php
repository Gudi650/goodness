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


        //get the Non current liabilities data from service file
        $nonCurrentLiabilities = app(NonCurrentLiabilitiesService::class)->getNonCurrentLiabilities();
        //dd($nonCurrentLiabilities);

        //get the current liabilities data from service file
        $currentLiabilities = app(CurrentLiabilitiesService::class)->getCurrentLiabilities();
        //dd($currentLiabilities);

        //get the current assets data from service file
        $currentAssets = app(CurrentAssetsService::class)->getCurrentAssets();
        //dd($currentAssets);


        //get the non current assets data from service file
        $nonCurrentAssets = app(NonCurrentAssetsService::class)->getNonCurrentAssets();
        //dd($nonCurrentAssets);

        //dd($currentAssets, $nonCurrentAssets, $currentLiabilities, $nonCurrentLiabilities, $equityLiabilities);


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



    //function to get the salaries and wages for the balance sheet report from the salaries table
    protected function getSalaries()
    {
        //get the salaries from the salaries table
        $salaries = Salary::where('effective_date', '<=', now())
            ->get();

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
            ->get();

        return $payableVAT;
    }

    //get the short Term Loans from the Liabilities table
    protected function getShortTermLoans()
    {
        //get the short term loans from the liabilities table
        $shortTermLoans = CreateLiability::where('term', 'Short-term')
            ->where('category', 'Loans & Borrowings')
            ->where('due_date', '<=', now())
            ->where('current_amount', '>', 0)
            ->get();

        return $shortTermLoans;
    }


}
