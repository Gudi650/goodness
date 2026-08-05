<?php

namespace App\Services\FAR;

use App\Models\CreateAssets;

class CalculateCurrentFar
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /*
    // fectch all the fixed assest first
    public function getFixedAssets()
    {
        $fixedAssets = CreateAssets::where('type', 'Fixed Asset')->get();

        return $fixedAssets;
    }

    // function to check and sort through the fixed assets and return only the assets that need to be depreciated and also calculate the depreciation value and current value of the fixed asset of the selected asset
    public function SortDepreciation()
    {
        $assets = $this->getFixedAssets();

        // check each assets and filter out the assets that need to be depreciated only
        $assetsToDepreciate = $assets->filter(function ($asset) {
            $acquisitionDate = $asset->acquisition_date;
            $currentDate = now();

            // check if the acquisition date is less than the current date
            return $acquisitionDate < $currentDate;
        });

        return $assetsToDepreciate;
    }

    // check if there are depreciated assests from the SortDepreciation method and if true calculate the depreciation value and current value of the fixed asset of the selected asset and return the values to the FAR page
    public function calculateDepreciation()
    {

        $assetsToDepreciate = $this->SortDepreciation();

        if ($assetsToDepreciate->isNotEmpty()) {

            

            // calculate the depreciation value and current value of the fixed asset of the selected asset
            foreach ($assetsToDepreciate as $asset) {
                $acquisitionDate = $asset->acquisition_date;
                $currentDate = now();
                $depreciationRate = $asset->depreciation_rate;

                // calculate the number of years between the acquisition date and the current date
                $years = $currentDate->diffInYears($acquisitionDate);

                // calculate the depreciation value
                $depreciationValue = ($depreciationRate / 100) * $asset->cost * $years;

                // calculate the current value
                $currentValue = $asset->cost - $depreciationValue;

                // update the asset with the new values
                $asset->depreciation_value = $depreciationValue;
                $asset->current_value = $currentValue;
                $asset->save();
            }
        }

        //refetch the fixed assets after the depreciation calculation and return them to the FAR page
        $fixedAssets = $this->getFixedAssets();

        return $fixedAssets;

    }
    */

    /**
     * Fetch all fixed assets.
     */
    public function getFixedAssets()
    {
        return CreateAssets::where('type', 'Fixed Asset')->get();
    }

    /**
     * Fetch only assets that need depreciation (filtering done in SQL).
     */
    public function getAssetsToDepreciate()
    {
        return CreateAssets::where('type', 'Fixed Asset')
            ->whereDate('acquired', '<', now())
            ->get();
    }

    /**
     * Calculate depreciation and update assets in bulk.
     */
    public function calculateDepreciation()
    {
        $assetsToDepreciate = $this->getAssetsToDepreciate();

        if ($assetsToDepreciate->isEmpty()) {
            return $this->getFixedAssets();
        }

        foreach ($assetsToDepreciate as $asset) {

            $acquisitionDate   = $asset->acquired;
            $currentDate       = now();
            $depreciationRate  = $asset->depreciation_value ?? 0;

            // Calculate number of whole months since acquisition (no partial month)
            $months = $currentDate->diffInMonths($acquisitionDate);

            // Use original value as base for depreciation (safe fallback to 0)
            $original = $asset->original_value ?? 0;

            // Depreciation value = annualRate% * original * (months / 12)
            $depreciationValue = ($depreciationRate / 100) * $original * ($months / 12);

            // Current value = original - accumulated depreciation (not negative)
            $currentValue = max(0, $original + $depreciationValue);

            // Update asset in memory
            $asset->current_value = $currentValue;
            
        }

        // Save all updates in one go (reduces DB calls)
        $assetsToDepreciate->each->save();

        // Return updated fixed assets
        return $this->getFixedAssets();
    }

}
