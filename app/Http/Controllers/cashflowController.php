<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\AssetRevaluation;
use App\Models\Dividends;
use App\Models\SharePremuims;
use App\Models\SharesDefinitions;
use App\Services\NetIncome;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CashFlowController extends Controller
{
    public function exportPdf()
    {
        $companyId = $this->resolveCompanyId();
        $companyName = $this->resolveCompanyName($companyId);
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;

        $previousSnapshot = $this->buildEquitySnapshot($companyId, $previousYear);
        $currentSnapshot = $this->buildEquitySnapshot($companyId, $currentYear);

        $currentNetIncome = $this->calculateNetIncomeForYear($companyId, $currentYear);
        $currentDividends = $this->getDividendsPaid($companyId, $currentYear);
        $previousDividends = $this->getDividendsPaid($companyId, $previousYear);

        $data = [
            'company' => $companyName,
            'title' => 'Statement of Changes in Equity',
            'period' => Carbon::create($currentYear, 12, 31)->format('d F Y'),
            'scale' => '(in thousands EUR)',
            'columns' => [
                'Share capital',
                'Share premium',
                'Retained earnings',
                'Revaluation surplus (PPE)',
                'Total equity attributable to the owners of the parent',
            ],
            'rows' => [
                ['label' => 'Balance at 1 Jan ' . ($previousYear) . '', 'values' => [0, 0, 0, 0, 0], 'strong' => true],
                ['label' => 'Changes in accounting policy', 'values' => [0, 0, 0, 0, 0]],
                ['label' => 'Restated balance', 'values' => [0, 0, 0, 0, 0], 'strong' => true],
                ['label' => 'Changes in equity for ' . $previousYear . ':', 'section' => true],
                ['label' => 'Dividends paid', 'values' => [0, 0, -1 * $previousDividends, 0, -1 * $previousDividends], 'indent' => 1],
                ['label' => 'Profit or loss', 'values' => [0, 0, $this->calculateNetIncomeForYear($companyId, $previousYear), 0, $this->calculateNetIncomeForYear($companyId, $previousYear)], 'indent' => 1, 'italic' => true],
                ['label' => 'Other comprehensive income', 'values' => [0, 0, 0, 0, 0], 'indent' => 1, 'italic' => true],
                ['label' => 'TCI for the year', 'values' => [0, 0, $this->calculateNetIncomeForYear($companyId, $previousYear), 0, $this->calculateNetIncomeForYear($companyId, $previousYear)], 'underline' => true],
                ['label' => 'Balance at 31 Dec ' . $previousYear . ':', 'values' => [
                    $previousSnapshot['share_capital'],
                    $previousSnapshot['share_premium'],
                    $previousSnapshot['retained_earnings'],
                    $previousSnapshot['revaluation_surplus'],
                    $previousSnapshot['total_equity'],
                ], 'strong' => true],
                ['label' => 'Changes in equity for ' . $currentYear . ':', 'section' => true],
                ['label' => 'Issue of shares', 'values' => [
                    max($currentSnapshot['share_capital'] - $previousSnapshot['share_capital'], 0),
                    max($currentSnapshot['share_premium'] - $previousSnapshot['share_premium'], 0),
                    0,
                    0,
                    max($currentSnapshot['total_equity'] - $previousSnapshot['total_equity'], 0),
                ], 'indent' => 1],
                ['label' => 'Dividends paid', 'values' => [0, 0, -1 * $currentDividends, 0, -1 * $currentDividends], 'indent' => 1],
                ['label' => 'Profit or loss', 'values' => [0, 0, $currentNetIncome, 0, $currentNetIncome], 'indent' => 1, 'italic' => true],
                ['label' => 'Other comprehensive income', 'values' => [0, 0, 0, 0, 0], 'indent' => 1, 'italic' => true],
                ['label' => 'TCI for the year', 'values' => [0, 0, $currentNetIncome, 0, $currentNetIncome], 'underline' => true],
                ['label' => 'Balance at 31 Dec ' . $currentYear . ':', 'values' => [
                    $currentSnapshot['share_capital'],
                    $currentSnapshot['share_premium'],
                    $currentSnapshot['retained_earnings'],
                    $currentSnapshot['revaluation_surplus'],
                    $currentSnapshot['total_equity'],
                ], 'strong' => true],
            ],
        ];

        $pdf = Pdf::loadView('reports.cash_flow', compact('data'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('cash_flow.pdf');
    }






    //function to get the dividends paid to shareholders from the dividends table in the database
    protected function resolveCompanyId(): ?int
    {
        return session('active_company_id') ?? Auth::user()?->company_id;
    }

    protected function resolveCompanyName(?int $companyId): string
    {
        if (! $companyId) {
            return 'Company';
        }

        return Company::query()->whereKey($companyId)->value('name') ?: 'Company';
    }

    protected function buildEquitySnapshot(?int $companyId, int $year): array
    {
        $shareCapital = $this->getShareCapital($companyId, $year);
        $sharePremium = $this->getSharePremium($companyId, $year);
        $retainedEarnings = $this->getRetainedEarnings($companyId, $year);
        $revaluationSurplus = $this->getRevaluationSurplus($companyId, $year);

        return [
            'share_capital' => $shareCapital,
            'share_premium' => $sharePremium,
            'retained_earnings' => $retainedEarnings,
            'revaluation_surplus' => $revaluationSurplus,
            'total_equity' => $shareCapital + $sharePremium + $retainedEarnings + $revaluationSurplus,
        ];
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

    protected function getSharePremium(?int $companyId, int $year): float
    {
        return (float) SharePremuims::query()
            ->when($companyId, fn ($query) => $query->where('company_id', $companyId))
            ->whereYear('created_at', '<=', $year)
            ->sum('total_premium');
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

    //function to get the retained suplus
    protected function getRevaluationSurplus(?int $companyId = null, ?int $year = null)
    {
        //get the revaluation surplus from the asset revaluation table in the database
        $query = AssetRevaluation::query();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($year) {
            $query->where(function ($subQuery) use ($year) {
                $subQuery->whereYear('date_of_revaluation', '<=', $year)
                    ->orWhereYear('created_at', '<=', $year);
            });
        }

        $revaluationSurplus = $query->sum('surplus');

        return (float) $revaluationSurplus;
    }

    protected function calculateNetIncomeForYear(?int $companyId = null, ?int $year = null): float
    {
        return app(NetIncome::class)->calculateNetIncome($companyId, $year);
    }


}