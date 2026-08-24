<?php

namespace App\Http\Controllers;

use App\Models\CreateAssets;
use App\Models\EquityDistribution;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\SharesDefinitions;
use App\Services\Finance\BalanceSheet\CurrentAssetsService;
use App\Services\Finance\BalanceSheet\CurrentLiabilitiesService;
use App\Services\Finance\BalanceSheet\NonCurrentAssetsService;
use App\Services\Finance\BalanceSheet\NonCurrentLiabilitiesService;
use App\Support\ReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;

class TrialBalanceController extends Controller
{


    //function to get report data for trial balance report
    //changes
    private function reportData(): array
    {
        ReportFilters::boot();

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

        //get other assets to display in the trial balance report
        $otherAssets = $this->getAssets();

        // Depreciation expense (Dr) = original_value - current_value (NBV)
        $depreciations = $this->getDepreciations();


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
            'otherAssets' => $otherAssets,
            'depreciations' => $depreciations,
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
        $query = Expense::where('category', 'Cost of Goods Sold (COGS)')
            ->where('status', 'issued');
        // Trial balance expense lines: company scope only (same as pre-filter behaviour).
        ReportFilters::current()->applyCompany($query);
        $costOfGoodsSold = $query->get()
            ->map(function ($expense) {
                return [
                    'name' => 'Cost of Goods Sold (COGS)',
                    'amount' => $expense->amount,
                    'type' => 'dr',
                ];
            })
            ->groupBy('name');

        return $costOfGoodsSold;
    }

    protected function getRevenues()
    {
        $query = Invoice::where('status', 'paid');
        ReportFilters::current()->applyCompany($query);

        return $query->get()
            ->map(function ($invoice) {
                return [
                    'name'   => 'Revenue',
                    'amount' => ($invoice->total_amount ?? 0) - ($invoice->tax_amount ?? 0),
                    'type'   => 'cr',
                ];
            })
            ->groupBy('name');
    }

    protected function getOperationalCosts()
    {
        $query = Expense::where('category', 'Operating Expenses')
            ->where('status', 'issued');
        ReportFilters::current()->applyCompany($query);
        $operationalCosts = $query->get()
            ->map(function ($expense) {
                return [
                    'name' => $expense->financeItem->item_name ?? $expense->sub_category ?? 'Uncategorized',
                    'amount' => $expense->amount,
                    'type' => 'dr',
                ];
            })
            ->groupBy('name');

        return $operationalCosts;
    }

    protected function getOtherExpenses()
    {
        $query = Expense::where('category', '!=', 'Cost of Goods Sold (COGS)')
            ->where('category', '!=', 'Operating Expenses')
            ->whereHas('financeItem')
            ->where('status', 'issued');
        ReportFilters::current()->applyCompany($query);
        $otherExpenses = $query->get()
            ->map(function ($expense) {
                return [
                    'name' => $expense->financeItem->item_name ?? $expense->sub_category ?? 'Uncategorized',
                    'amount' => $expense->amount,
                    'type' => 'dr',
                ];
            })
            ->groupBy('name');

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

    //get all other assets
    protected function getAssets()
    {
        $query = CreateAssets::whereHas('category', function ($query) {
            $query->where('category', '!=','Vehicle Assets')
                ->where('category', '!=','Property Assets')
                ->where('category', '!=','Investment Assets')
                ->where('category', '!=','Intangible Assets');
        })
            ->where('current_value', '>', 0);
        ReportFilters::current()->applyCompany($query);
        $otherAssets = $query->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->category->category ?? 'Uncategorized',
                    'amount' => $asset->current_value,
                    'type' => 'dr',
                ];
            })
            ->groupBy('name');

        return $otherAssets;
    }

    /*
    protected function getEquities()
    {
        $query = SharesDefinitions::query();
        ReportFilters::current()->applyCompany($query);
        $equities = $query->get()
            ->map(function ($share) {
                return [
                    'name' => $share->company_id,
                    'amount' => $share->share_value * $share->issued_shares,
                    'type' => 'cr',
                ];
            })
            ->groupBy('name');

        $totalEquities = 0;

        foreach ($equities as $equityGroup) {
            $totalEquities += $equityGroup->sum('amount');
        }

        return $totalEquities;
    } */

    //use equityDefinitions table to get the equities
    protected function getEquities()
    {
        $query = EquityDistribution::query();
        ReportFilters::current()->applyCompany($query);
        $equities = $query->get()
            ->map(function ($equity) {
                return [
                    'name' => $equity->company->name,
                    'amount' => $equity->value_held,
                    'type' => 'cr',
                ];
            })
            ->groupBy('name');

        $totalEquities = 0;

        foreach ($equities as $equityGroup) {
            $totalEquities += $equityGroup->sum('amount');
        }


        return $totalEquities;
    }

    // Depreciation expense (Dr) so TB balances with assets at NBV
    protected function getDepreciations()
    {
        $query = CreateAssets::query();
        ReportFilters::current()->applyCompany($query);

        $amount = $query->get()->sum(function (CreateAssets $asset) {
            return max(0, (float) $asset->original_value - (float) $asset->current_value);
        });

        if ($amount <= 0) {
            return collect();
        }

        return collect([
            [
                'name' => 'Depreciation',
                'amount' => (float) $amount,
                'type' => 'dr',
            ],
        ])->groupBy('name');
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
