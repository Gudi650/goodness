<?php

namespace App\Services\Finance;

use App\Models\CreateAssets;
use App\Support\ReportFilters;

class AssetDisposalService
{
    /** Gain/(loss) = proceeds − carrying value at disposal. */
    public function gainOrLoss(?int $companyId = null, ?int $year = null): float
    {
        $query = CreateAssets::query()
            ->whereIn('status', ['Sold', 'Disposed', 'Written Off'])
            ->whereNotNull('disposal_date');

        if ($companyId) {
            $query->where('company_id', $companyId);
        } else {
            ReportFilters::current()->applyCompany($query);
        }

        if ($year) {
            $query->whereYear('disposal_date', $year);
        } else {
            ReportFilters::current()->applyDate($query, 'disposal_date');
        }

        return (float) $query->get()->sum(function (CreateAssets $asset) {
            return (float) ($asset->disposal_proceeds ?? 0) - (float) ($asset->disposal_carrying_value ?? 0);
        });
    }

    /** Cash proceeds from disposals (investing inflow). */
    public function cashProceeds(?int $companyId = null, ?int $year = null): float
    {
        $query = CreateAssets::query()
            ->whereIn('status', ['Sold', 'Disposed', 'Written Off'])
            ->whereNotNull('disposal_date');

        if ($companyId) {
            $query->where('company_id', $companyId);
        } else {
            ReportFilters::current()->applyCompany($query);
        }

        if ($year) {
            $query->whereYear('disposal_date', $year);
        } else {
            ReportFilters::current()->applyDate($query, 'disposal_date');
        }

        return (float) $query->sum('disposal_proceeds');
    }
}
