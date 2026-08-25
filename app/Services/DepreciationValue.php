<?php

namespace App\Services;

use App\Models\CreateAssets;
use App\Support\ReportFilters;

class DepreciationValue
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //

    }


    //get depreciation value of all assets
    public function getDepreciationValue(): float
    {
        $query = CreateAssets::where('current_value', '>', 0);
        ReportFilters::current()->applyCompany($query);
        $depreciationValue = $query->get()->sum(function ($asset) {
            return (float) ($asset->original_value - $asset->current_value);
        });
        return $depreciationValue;
    }

}
