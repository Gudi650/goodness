<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Services\Finance\BalanceSheet\CurrentAssetsService;
use App\Services\Finance\BalanceSheet\CurrentLiabilitiesService;
use App\Services\Finance\BalanceSheet\NonCurrentAssetsService;
use App\Services\Finance\BalanceSheet\NonCurrentLiabilitiesService;
use Barryvdh\DomPDF\Facade\Pdf;

class TrialBalanceController extends Controller
{


    //function to get report data for trial balance report
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

        //dd($currentAssets, $nonCurrentAssets, $currentLiabilities, $nonCurrentLiabilities, $costOfGoodsSold, $revenues, $operationalCosts);

        return [
            'costOfGoodsSold' => $costOfGoodsSold,
            'revenues' => $revenues,
            'operationalCosts' => $operationalCosts,
            'nonCurrentLiabilities' => $nonCurrentLiabilities,
            'currentLiabilities' => $currentLiabilities,
            'currentAssets' => $currentAssets,
            'nonCurrentAssets' => $nonCurrentAssets,
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


        return view('reports.trial_balance', $reportData, array_merge($reportData, [
            'totalDr' => $totalDr,
            'totalCr' => $totalCr,
        ]));
    }



    public function exportPdf()
    {
        // Replace this array with your Eloquent Query (e.g., Account::all())
        $accounts = [
            ['name' => 'Purchases A/c', 'type' => 'dr', 'amount' => 45000],
            ['name' => 'Sales A/c', 'type' => 'cr', 'amount' => 85000],
            ['name' => 'Purchase Return A/c', 'type' => 'cr', 'amount' => 1200],
            ['name' => 'Sales Return A/c', 'type' => 'dr', 'amount' => 1500],
            ['name' => 'Cash A/c', 'type' => 'dr', 'amount' => 12500],
            ['name' => 'Bank A/c', 'type' => 'dr', 'amount' => 28000],
            ['name' => 'Capital A/c', 'type' => 'cr', 'amount' => 50000],
            ['name' => 'Salaries A/c', 'type' => 'dr', 'amount' => 8000],
            ['name' => 'Furniture A/c', 'type' => 'dr', 'amount' => 15000],
            ['name' => 'Bills Payable A/c', 'type' => 'cr', 'amount' => 4200],
            ['name' => 'Bills Receivable A/c', 'type' => 'dr', 'amount' => 6000],
            ['name' => 'Debtors A/c', 'type' => 'dr', 'amount' => 18500],
            ['name' => 'Creditors A/c', 'type' => 'cr', 'amount' => 11200],
            ['name' => 'Drawings A/c', 'type' => 'dr', 'amount' => 3000],
            ['name' => 'Loan A/c', 'type' => 'cr', 'amount' => 20000],
            ['name' => 'Interest Received A/c', 'type' => 'cr', 'amount' => 600],
            ['name' => 'Interest Paid A/c', 'type' => 'dr', 'amount' => 400],
            ['name' => 'Carriage Inward and Outward A/c', 'type' => 'dr', 'amount' => 1100],
            ['name' => 'Opening stock A/c', 'type' => 'dr', 'amount' => 14000],
            ['name' => 'Machinery A/c', 'type' => 'dr', 'amount' => 35000],
            ['name' => 'Prepaid Expenses A/c', 'type' => 'dr', 'amount' => 900],
            ['name' => 'Outstanding Expenses A/c', 'type' => 'cr', 'amount' => 1300],
            ['name' => 'Discount Received A/c', 'type' => 'cr', 'amount' => 800],
            ['name' => 'Discount Allowed A/c', 'type' => 'dr', 'amount' => 500],
        ];

        $totalDr = 0;
        $totalCr = 0;

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

        // merge all the data into a single array
        




        foreach ($accounts as $account) {
            if ($account['type'] === 'dr') {
                $totalDr += $account['amount'];
            } else {
                $totalCr += $account['amount'];
            }
        }

        $pdf = Pdf::loadView('reports.trial_balance', compact('accounts', 'totalDr', 'totalCr', 'nonCurrentLiabilities', 'currentLiabilities', 'currentAssets', 'nonCurrentAssets'));

        return $pdf->stream('trial_balance.pdf');

    }

    // function to get cost of goods sold  going to use it in trial balance report
    protected function getCostOfGoodsSold()
    {
        // get the cost of goods sold from the products table
        $costOfGoodsSold = Expense::where('category', 'Cost of Goods Sold (COGS)')
            ->where('status', 'draft')
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
        $revenues = Invoice::where('status', 'draft')
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
            ->where('status', 'draft')
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

    //function to get the total of all DEBIT entries in the trial balance report
    //use the collections liabiltites, assets, to sum the total of all debit entries
    protected function getTotalDr($reportData)
    {
        $totalDr = 0;

        foreach ($reportData as $data) {
            if ($data instanceof \Illuminate\Support\Collection) {
                // If grouped, flatten the sub‑Collections
                if ($data->first() instanceof \Illuminate\Support\Collection) {
                    foreach ($data as $group) {
                        $totalDr += $group->where('type', 'dr')->sum('amount');
                    }
                } else {
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

        return $totalDr;
    }



    //total of all CREDIT entries in the trial balance report
    protected function getTotalCr($reportData)
    {
        $totalCr = 0;
        foreach ($reportData as $data) {
            // If it's a Collection, sum directly
            if ($data instanceof \Illuminate\Support\Collection) {
                $totalCr += $data->where('type', 'cr')->sum('amount');
            }

            // If it's an array of Collections (like grouped assets/liabilities)
            elseif (is_array($data)) {
                foreach ($data as $group) {
                    if ($group instanceof \Illuminate\Support\Collection) {
                        $totalCr += $group->where('type', 'cr')->sum('amount');
                    }
                }
            }
        }

        return $totalCr;
    }



}
