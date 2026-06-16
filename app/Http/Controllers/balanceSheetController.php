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
        
        $equityLiabilities = [

            'equity' => [
                ['name' => 'Share Capital', 'amount' => 400000],
                ['name' => 'Retained Earnings', 'amount' => 100000],
                ['name' => 'Other Equity', 'amount' => 15000],
            ]

        ];
        

        $totalEquity = collect($equityLiabilities['equity'])->sum('amount');




        //get the Non current liabilities data from service file
        $nonCurrentLiabilities = app(NonCurrentLiabilitiesService::class)->getNonCurrentLiabilities();

        //get the current liabilities data from service file
        $currentLiabilities = app(CurrentLiabilitiesService::class)->getCurrentLiabilities();

        //get the current assets data from service file
        $currentAssets = app(CurrentAssetsService::class)->getCurrentAssets();

        //get the non current assets data from service file
        $nonCurrentAssets = app(NonCurrentAssetsService::class)->getNonCurrentAssets();



        return [

            'totalEquity' => $totalEquity,
            'equityLiabilities' => $equityLiabilities,
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
