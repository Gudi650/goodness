<?php

namespace App\Http\Controllers;

use App\Models\CreateAssets;
use App\Models\CreateLiability;
use App\Models\Dividends;
use App\Models\Expense;
use App\Models\EquityDistribution;
use App\Models\Salary;
use App\Models\SharesDefinitions;
use App\Services\Finance\BalanceSheet\CurrentAssetsService;
use App\Services\Finance\BalanceSheet\CurrentLiabilitiesService;
use App\Services\Finance\BalanceSheet\NonCurrentAssetsService;
use App\Services\Finance\BalanceSheet\NonCurrentLiabilitiesService;
use App\Services\NetIncome;
use App\Support\ReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class balanceSheetController extends Controller
{
    private function reportData(): array
    {
        ReportFilters::boot();

        //get the Non current liabilities data from service file
        $nonCurrentLiabilities = app(NonCurrentLiabilitiesService::class)->getNonCurrentLiabilities();

        //get the current liabilities data from service file
        $currentLiabilities = app(CurrentLiabilitiesService::class)->getCurrentLiabilities();

        //get the current assets data from service file
        $currentAssets = app(CurrentAssetsService::class)->getCurrentAssets();

        //get the non current assets data from service file
        $nonCurrentAssets = app(NonCurrentAssetsService::class)->getNonCurrentAssets();

        //get company id
        $companyId = $this->resolveCompanyId();
        $year = ReportFilters::current()->year();

        //get the share capital
        $shareCapital = $this->getShareCapital($companyId, null);

        //get retained earnings (all periods unless a single company scope is set — matches prior behaviour)
        $retainedEarnings = $this->getRetainedEarnings($companyId, $companyId ? $year : null);

        $otherEquity = $this->getOtherEquity($companyId);

        //get other assets
        $otherAssets = $this->getAssets();

        // Move VAT between Assets and Liabilities depending on its type
        if (isset($currentLiabilities['payable_vat'])) {

            $vat = $currentLiabilities['payable_vat']->first();

            if ($vat && $vat['type'] === 'dr') {

                // VAT Receivable → Current Assets
                $currentAssets['vat_receivable'] = collect([$vat]);

                unset($currentLiabilities['payable_vat']);

            } else {

                // VAT Payable → keep in Current Liabilities
                $currentLiabilities['vat_payable'] = $currentLiabilities['payable_vat'];

                unset($currentLiabilities['payable_vat']);
            }
        }

        $equityLiabilities = [
            'equity' => [
                ['name' => 'Share Capital', 'amount' => $shareCapital],
                ['name' => 'Retained Earnings', 'amount' => $retainedEarnings],
                ['name' => 'Other Equity', 'amount' => $otherEquity],
            ]
        ];

        $totalEquity = collect($equityLiabilities['equity'])->sum('amount');



        return [

            'totalEquity' => $totalEquity,
            'equityLiabilities' => $equityLiabilities,
            'nonCurrentLiabilities' => $nonCurrentLiabilities,
            'currentLiabilities' => $currentLiabilities,
            'nonCurrentAssets' => $nonCurrentAssets,
            'currentAssets' => $currentAssets,
            'otherAssets' => $otherAssets,

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

    /*
    protected function getShareCapital(?int $companyId = null, ?int $year = null): float
    {
        $definitions = SharesDefinitions::query()
            ->when($companyId, fn ($query) => $query->where('company_id', $companyId))
            ->get();

        if ($definitions->isEmpty()) {
            return 0.0;
        }

        $total = $definitions->sum(function ($d) {
            return (float) ($d->issued_shares ?? 0) * (float) ($d->share_value ?? 0);
        });

        return (float) $total;
    }*/

    //get share capital from equity distribution table
    protected function getShareCapital(?int $companyId = null, ?int $year = null): float
    {
        $query = EquityDistribution::query();
        ReportFilters::current()->applyCompany($query);
        $shareCapital = $query->get()
            ->map(function ($equity) {
                return [
                    'name' => $equity->company->name,
                    'amount' => $equity->value_held,
                ];
            })
            ->sum('amount');
        return $shareCapital;
    }


    //function to get returned earnings
    protected function getRetainedEarnings(?int $companyId = null, ?int $year = null)
    {
        $dividends = $this->getDividendsPaid($companyId, $year);

        //get the net income from the net income service
        $netIncome = $this->calculateNetIncomeForYear($companyId, $year);

        //get the retained earnings by subtracting the dividends paid from the net income
        $retainedEarnings = $netIncome - $dividends;

        return (float) $retainedEarnings;
    }

    protected function calculateNetIncomeForYear(?int $companyId = null, ?int $year = null): float
    {
        return app(NetIncome::class)->calculateNetIncome($companyId, $year);
    }


    protected function getDividendsPaid(?int $companyId = null, ?int $year = null): float
    {
        $query = Dividends::query()->where('status', 'Declared');
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return (float) $query->sum('amount');

    }

    protected function getOtherEquity(?int $companyId = null): float
    {
        //return (float) EquityDistribution::query()->sum('value_held');
        return 0.0;
    }

        //function to get the dividends paid to shareholders from the dividends table in the database
    protected function resolveCompanyId(): ?int
    {
        $filters = ReportFilters::current();
        if ($filters->scope === 'company' && $filters->companyId) {
            return $filters->companyId;
        }

        if ($filters->scope === 'all') {
            return null;
        }

        $companyId = session('active_company_id') ?? Auth::user()?->company_id;

        if ($companyId) {
            return (int) $companyId;
        }

        return \App\Models\Company::query()->value('id');
    }


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




}
