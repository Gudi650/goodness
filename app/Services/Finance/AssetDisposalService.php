<?php

namespace App\Services\Finance;

use App\Models\CreateAssets;
use App\Support\ReportFilters;
use Illuminate\Support\Facades\Schema;

class AssetDisposalService
{
    /** Gain/(loss) = proceeds − carrying value at disposal. */
    public function gainOrLoss(?int $companyId = null, ?int $year = null): float
    {
        if (! Schema::hasTable('create_assets')) {
            return 0.0;
        }

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
        if (! Schema::hasTable('create_assets')) {
            return 0.0;
        }

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
