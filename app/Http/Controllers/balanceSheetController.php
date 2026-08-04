<?php

namespace App\Http\Controllers;

use App\Models\CreateLiability;
use App\Models\Dividends;
use App\Models\Expense;
use App\Models\Salary;
use App\Models\SharesDefinitions;
use App\Services\Finance\BalanceSheet\CurrentAssetsService;
use App\Services\Finance\BalanceSheet\CurrentLiabilitiesService;
use App\Services\Finance\BalanceSheet\NonCurrentAssetsService;
use App\Services\Finance\BalanceSheet\NonCurrentLiabilitiesService;
use App\Services\NetIncome;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class balanceSheetController extends Controller
{
    private function reportData(): array
    {
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
        $year = now()->year;

        //get the share capital
        $shareCapital = $this->getShareCapital($companyId,$year);

        //get retained earnings 
        $retainedEarnings = $this->getRetainedEarnings($companyId,$year);

        $equityLiabilities = [
            'equity' => [
                ['name' => 'Share Capital', 'amount' => $shareCapital],
                ['name' => 'Retained Earnings', 'amount' => $retainedEarnings],
                ['name' => 'Other Equity', 'amount' => 15000],
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

    protected function getShareCapital(?int $companyId, int $year): float
    {
        $definition = SharesDefinitions::query()
            ->when($companyId, fn ($query) => $query->where('company_id', $companyId))
            ->whereYear('created_at', '<=', $year)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->first();

        if (! $definition) {
            return 0.0;
        }

        $issuedShares = (float) ($definition->issued_shares ?? 0);
        $shareValue = (float) ($definition->share_value ?? 0);

        return $issuedShares * $shareValue;
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

        if ($year) {
            $query->where(function ($subQuery) use ($year) {
                $subQuery->whereYear('paid_at', $year)
                    ->orWhere(function ($paidQuery) use ($year) {
                        $paidQuery->whereNull('paid_at')
                            ->whereYear('declared_at', $year);
                    });
            });
        }

        $dividendsPaid = $query->sum('amount');

        return (float) $dividendsPaid;
    }

        //function to get the dividends paid to shareholders from the dividends table in the database
    protected function resolveCompanyId(): ?int
    {
        return session('active_company_id') ?? Auth::user()?->company_id;
    }




}
