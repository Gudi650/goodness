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
            ->whereDate('acquired', '<', now()->startOfMonth()) // Only assets acquired before the current month// Only assets that still have value
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
