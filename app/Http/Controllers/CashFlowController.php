<?php

namespace App\Http\Controllers;

use App\Models\AssetRevaluation;
use App\Models\Dividends;
use App\Models\EquityDistribution;
use App\Models\SharePremuims;
use App\Services\NetIncome;
use App\Support\ReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;

class CashFlowController extends Controller
{
    public function previewPdf()
    {
        ReportFilters::boot();

        return $this->renderReportPdf()->stream('equity_statement.pdf');
    }

    public function downloadPdf()
    {
        ReportFilters::boot();

        return $this->renderReportPdf()->download('equity_statement.pdf');
    }

    protected function resolveCompanyId(): ?int
    {
        return ReportFilters::current()->resolveCompanyId();
    }

    protected function buildReportData(): array
    {
        $companyId = $this->resolveCompanyId();
        $companyName = ReportFilters::current()->displayCompanyName();
        $currentYear = ReportFilters::current()->year();
        $previousYear = $currentYear - 1;

        $previousSnapshot = $this->buildEquitySnapshot($companyId, $previousYear);
        $currentSnapshot = $this->buildEquitySnapshot($companyId, $currentYear);

        $currentNetIncome = $this->calculateNetIncomeForYear($companyId, $currentYear);
        $previousNetIncome = $this->calculateNetIncomeForYear($companyId, $previousYear);
        $currentDividends = $this->getDividendsPaid($companyId, $currentYear);
        $previousDividends = $this->getDividendsPaid($companyId, $previousYear);

        $shareCapitalIssued = max($currentSnapshot['share_capital'] - $previousSnapshot['share_capital'], 0);
        $sharePremiumIssued = max($currentSnapshot['share_premium'] - $previousSnapshot['share_premium'], 0);

        return [
            'company' => $companyName,
            'title' => 'Statement of Changes in Equity',
            'period' => 'As at ' . now()->format('d M Y'),
            'scale' => '(in thousands Tshs)',
            'columns' => [
                'Share capital',
                'Share premium',
                'Retained earnings',
                'Revaluation surplus (PPE)',
                'Total equity attributable to the owners of the parent',
            ],

            'rows' => [

                ['label' => 'Balance at 1 Jan ' . $previousYear, 'values' => [0, 0, 0, 0, 0], 'strong' => true],
                ['label' => 'Changes in accounting policy', 'values' => [0, 0, 0, 0, 0]],
                ['label' => 'Restated balance', 'values' => [0, 0, 0, 0, 0], 'strong' => true],
                ['label' => 'Changes in equity for ' . $previousYear . ':', 'section' => true],
                ['label' => 'Dividends paid', 'values' => [0, 0, -1 * $previousDividends, 0, -1 * $previousDividends], 'indent' => 1],
                ['label' => 'Profit or loss', 'values' => [0, 0, $previousNetIncome, 0, $previousNetIncome], 'indent' => 1, 'italic' => true],
                ['label' => 'Other comprehensive income', 'values' => [0, 0, 0, 0, 0], 'indent' => 1, 'italic' => true],
                ['label' => 'TCI for the year', 'values' => [0, 0, 0, 0, 0], 'underline' => true],

                ['label' => 'Balance at 31 Dec ' . $previousYear . ':', 'values' => [
                    $previousSnapshot['share_capital'],
                    $previousSnapshot['share_premium'],
                    $previousSnapshot['retained_earnings'],
                    $previousSnapshot['revaluation_surplus'],
                    $previousSnapshot['total_equity'],
                ], 'strong' => true],

                ['label' => 'Changes in equity for ' . $currentYear . ':', 'section' => true],

                ['label' => 'Issue of shares', 'values' => [
                    $shareCapitalIssued,
                    $sharePremiumIssued,
                    0,
                    0,
                    $shareCapitalIssued + $sharePremiumIssued,
                ], 'indent' => 1],

                ['label' => 'Dividends paid', 'values' => [0, 0, -1 * $currentDividends, 0, -1 * $currentDividends], 'indent' => 1],
                ['label' => 'Profit or loss', 'values' => [0, 0, $currentNetIncome, 0, $currentNetIncome], 'indent' => 1, 'italic' => true],
                ['label' => 'Other comprehensive income', 'values' => [0, 0, 0, 0, 0], 'indent' => 1, 'italic' => true],
                //['label' => 'TCI for the year', 'values' => [0, 0, $currentNetIncome, 0, $currentNetIncome], 'underline' => true],
                ['label' => 'TCI for the year', 'values' => [0, 0, 0, 0, 0], 'underline' => true],

                ['label' => 'Balance at 31 Dec ' . $currentYear . ':', 'values' => [
                    $currentSnapshot['share_capital'],
                    $currentSnapshot['share_premium'],
                    $currentSnapshot['retained_earnings'],
                    $currentSnapshot['revaluation_surplus'],
                    $currentSnapshot['total_equity'],
                ], 'strong' => true],

            ],
        ];
    }

    protected function renderReportPdf()
    {
        $data = $this->buildReportData();

        $pdf = Pdf::loadView('reports.cash_flow', compact('data'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf;
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

    protected function applyCompanyScope($query, ?int $companyId)
    {
        if ($companyId) {
            $query->where('company_id', $companyId);
        } else {
            ReportFilters::current()->applyCompany($query);
        }

        return $query;
    }

    protected function getShareCapital(?int $companyId, int $year): float
    {
        $query = EquityDistribution::query();
        $this->applyCompanyScope($query, $companyId);
        $query->whereYear('created_at', '<=', $year);

        return (float) $query->sum('value_held');
    }

    protected function getSharePremium(?int $companyId, int $year): float
    {
        $query = SharePremuims::query();
        $this->applyCompanyScope($query, $companyId);
        $query->whereYear('created_at', '<=', $year);

        return (float) $query->sum('total_premium');
    }

    protected function getDividendsPaid(?int $companyId = null, ?int $year = null): float
    {
        $query = Dividends::query()->where('status', 'Declared');
        $this->applyCompanyScope($query, $companyId);

        if ($year) {
            $query->where(function ($subQuery) use ($year) {
                $subQuery->whereYear('paid_at', $year)
                    ->orWhere(function ($paidQuery) use ($year) {
                        $paidQuery->whereNull('paid_at')
                            ->whereYear('declared_at', $year);
                    });
            });
        }

        return (float) $query->sum('amount');
    }

    protected function getRetainedEarnings(?int $companyId = null, ?int $year = null)
    {
        $dividends = $this->getDividendsPaid($companyId, $year);
        $netIncome = $this->calculateNetIncomeForYear($companyId, $year);

        return (float) ($netIncome - $dividends);
    }

    protected function getRevaluationSurplus(?int $companyId = null, ?int $year = null)
    {
        $query = AssetRevaluation::query();
        $this->applyCompanyScope($query, $companyId);

        if ($year) {
            $query->where(function ($subQuery) use ($year) {
                $subQuery->whereYear('date_of_revaluation', '<=', $year)
                    ->orWhereYear('created_at', '<=', $year);
            });
        }

        return (float) $query->sum('surplus');
    }

    protected function calculateNetIncomeForYear(?int $companyId = null, ?int $year = null): float
    {
        return app(NetIncome::class)->calculateNetIncome($companyId, $year);
    }
    
}
