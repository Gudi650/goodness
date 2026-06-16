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
        $accounts = array_merge(
            //$accounts,
            $costOfGoodsSold->toArray(),
            $revenues->toArray(),
            $operationalCosts->toArray(),
            $currentLiabilities['short_term_loans']->toArray(),
            $currentLiabilities['accrued_expenses']->toArray(),
            $currentLiabilities['interest_payables']->toArray(),
            $currentLiabilities['salaries']->toArray(),
            $currentLiabilities['payable_vat']->toArray(),
            $nonCurrentLiabilities->toArray(),
            $currentAssets['inventory_assets']->toArray(),
            $currentAssets['cash_and_bank_balances']->toArray(),
            $nonCurrentAssets['property_assets']->toArray(),
            $nonCurrentAssets['vehicle_assets']->toArray(),
            $nonCurrentAssets['intangible_assets']->toArray(),
            $nonCurrentAssets['investment_assets']->toArray(),

        );




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
            });

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
            });

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
            });

        //return in an array format
        return $operationalCosts;
    }

}
