<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\SharesDefinitions;
use App\Services\Finance\BalanceSheet\CurrentAssetsService;
use App\Services\Finance\BalanceSheet\CurrentLiabilitiesService;
use App\Services\Finance\BalanceSheet\NonCurrentAssetsService;
use App\Services\Finance\BalanceSheet\NonCurrentLiabilitiesService;
use Barryvdh\DomPDF\Facade\Pdf;

class TrialBalanceController extends Controller
{


    //function to get report data for trial balance report
    //changes
    private function reportData(): array
    {

        // get the Non current liabilities data from service file
        $nonCurrentLiabilities = app(NonCurrentLiabilitiesService::class)->getNonCurrentLiabilities();

        // get the current liabilities data from service file
        $currentLiabilities = app(CurrentLiabilitiesService::class)->getCurrentLiabilities();

        // get the current assets data from service file
        $currentAssets = app(CurrentAssetsService::class)->getCurrentAssets();

        // get the non current assets data from service file
        $nonCurrentAssets = app(NonCurrentAssetsService::class)->getNonCurrentAssets();


        //get the cost of goods sold from the service file
        $costOfGoodsSold = $this->getCostOfGoodsSold();

        //get the revenues from the service file
        $revenues = $this->getRevenues();

        //get the operational costs from the service file
        $operationalCosts = $this->getOperationalCosts();

        //get other other expenses as well here
        $otherExpenses = $this->getOtherExpenses();

        //get the equities to display in the trial balance report
        $equities = $this->getEquities();


        return [
            'costOfGoodsSold' => $costOfGoodsSold,
            'revenues' => $revenues,
            'operationalCosts' => $operationalCosts,
            'nonCurrentLiabilities' => $nonCurrentLiabilities,
            'currentLiabilities' => $currentLiabilities,
            'currentAssets' => $currentAssets,
            'nonCurrentAssets' => $nonCurrentAssets,
            'otherExpenses' => $otherExpenses,
            'equities' => $equities,
        ];

    }

    //function to index the data in the trial balance report
    public function index()
    {
        $reportData = $this->reportData();

        //get the total of all debit entries in the trial balance report
        $totalDr = $this->getTotalDr($reportData);

        //get teh total of all credit entries in the trial balance report
        $totalCr = $this->getTotalCr($reportData);


        return view('reports.trial_balance', array_merge($reportData, [
            'totalDr' => $totalDr,
            'totalCr' => $totalCr,
        ]));
    }

    public function exportPdf()
    {
        $reportData = $this->reportData();

        //get the total of all debit entries in the trial balance report
        $totalDr = $this->getTotalDr($reportData);

        //get teh total of all credit entries in the trial balance report
        $totalCr = $this->getTotalCr($reportData);

        $pdf = Pdf::loadView('reports.trial_balance', array_merge($this->reportData(), [
            'showActions' => false,
            'totalDr' => $totalDr,
            'totalCr' => $totalCr,
        ]));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('trial_balance.pdf');
    }

    // function to get cost of goods sold  going to use it in trial balance report
    protected function getCostOfGoodsSold()
    {
        // get the cost of goods sold from the products table
        $costOfGoodsSold = Expense::where('category', 'Cost of Goods Sold (COGS)')
            ->where('status', 'issued')
            ->get()
            ->map(function ($expense) {
                return [
                    'name' => 'Cost of Goods Sold (COGS)',
                    'amount' => $expense->amount,
                    'type' => 'dr', // Assuming assets are debit entries
                ];
            })
            ->groupBy('name'); // Group by name to aggregate amounts for the same account

        // return in an array format
        return $costOfGoodsSold;
    }

    // function to get the revenues from invoices table in the database
    // use the invoices which are paid here
    protected function getRevenues()
    {
        // fetch revenues from the database
        $revenues = Invoice::where('status', 'paid')
            ->get()
            ->map(function ($invoice) {
                return [
                    'name' => 'Revenue',
                    'amount' => $invoice->total_amount,
                    'type' => 'cr', // Assuming revenues are credit entries
                ];
            })
            ->groupBy('name'); // Group by name to aggregate amounts for the same account

        // return in an array format
        return $revenues;
    }

    //function to get the operational costs from expenses table in the db 
    protected function getOperationalCosts()
    {
        //fetch the operational costs from the database
        $operationalCosts = Expense::where('category', 'Operating Expenses')
            ->where('status', 'issued')
            ->get()
            ->map(function ($expense) {
                return [
                    'name' => 'Operational Expenses',
                    'amount' => $expense->amount,
                    'type' => 'dr', // Assuming expenses are debit entries
                ];
            })
            ->groupBy('name'); // Group by name to aggregate amounts for the same account

        //return in an array format
        return $operationalCosts;
    }

    //okay get other expenses with their categories
    protected function getOtherExpenses()
    {
        //fetch the other expenses from the database
        $otherExpenses = Expense::where('category', '!=', 'Cost of Goods Sold (COGS)')
            ->where('category', '!=', 'Operating Expenses')
            ->whereHas('financeItem' ) // Ensure it queries except finance items of Salaries and Wages
            ->whereHas('financeItem', function ($query) {
                $query->where('item_name', '!=', 'Salaries and Wages');
            })
            ->where('status', 'issued')
            ->get()
            ->map(function ($expense) {
                return [
                    //'name' => optional($expense->financeItem)->item_name ?? $expense->sub_category ?? 'Uncategorized',
                    'name' => $expense->financeItem->item_name ?? $expense->sub_category ?? 'Uncategorized',
                    'amount' => $expense->amount,
                    'type' => 'dr', // Assuming expenses are debit entries
                ];
            })
            ->groupBy('name'); // Group by name to aggregate amounts for the same account
        



        //return in an array format
        //dd($otherExpenses);
        return $otherExpenses;
    }

    //get total of the otherExpenses
    protected function getTotalOtherExpenses()
    {
        $otherExpenses = $this->getOtherExpenses();

        $totalOtherExpenses = 0;

        foreach ($otherExpenses as $expenseGroup) {
            $totalOtherExpenses += $expenseGroup->sum('amount');
        }

        return (float) $totalOtherExpenses;
    }

    //get the equities to display in the trial balance report
    protected function getEquities()
    {
        //get the equities from the database
        $equities = SharesDefinitions::all()
            ->map(function ($share) {
                return [
                    'name' => $share->company_id,
                    'amount' => $share->share_value * $share->issued_shares,
                    'type' => 'cr', // Assuming equities are credit entries
                ];
            })
            ->groupBy('name'); // Group by name to aggregate amounts for the same account

        //get total of the equities
        $totalEquities = 0;

        foreach ($equities as $equityGroup) {
            $totalEquities += $equityGroup->sum('amount');
        }

        //return in an array format
        return $totalEquities;

    }

    //function to get the total of all DEBIT entries in the trial balance report
    //use the collections liabiltites, assets, to sum the total of all debit entries
    protected function getTotalDr($reportData)
    {
        $totalDr = 0;

        foreach ($reportData as $data) {

            if ($data instanceof \Illuminate\Support\Collection) {

                // Handle grouped collections
                if ($data->first() instanceof \Illuminate\Support\Collection) {

                    foreach ($data as $group) {
                        $totalDr += $group->where('type', 'dr')->sum('amount');
                    }

                } else {

                    // Handle normal collections
                    $totalDr += $data->where('type', 'dr')->sum('amount');
                }

            } elseif (is_array($data)) {

                foreach ($data as $group) {

                    if ($group instanceof \Illuminate\Support\Collection) {
                        $totalDr += $group->where('type', 'dr')->sum('amount');
                    }

                }

            }

        }

        //add the total of other expenses to the totalDr
        $totalDr += $this->getTotalOtherExpenses();

        return $totalDr;
    }



    //total of all CREDIT entries in the trial balance report
    protected function getTotalCr($reportData)
    {
        $totalCr = 0;

        foreach ($reportData as $data) {

            if ($data instanceof \Illuminate\Support\Collection) {

                // grouped collection
                if ($data->first() instanceof \Illuminate\Support\Collection) {

                    foreach ($data as $group) {
                        $totalCr += $group->where('type', 'cr')->sum('amount');
                    }

                } else {

                    $totalCr += $data->where('type', 'cr')->sum('amount');
                }

            } elseif (is_array($data)) {

                foreach ($data as $group) {

                    if ($group instanceof \Illuminate\Support\Collection) {
                        $totalCr += $group->where('type', 'cr')->sum('amount');
                    }

                }

            }

        }

        //add the equities in the creadit as well here

        $totalCr += $this->getEquities();

        //dd($totalCr);

        return $totalCr;
    }



}
