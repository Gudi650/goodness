<?php

namespace App\Services\Finance\BalanceSheet;

use App\Models\CreateAssets;
use App\Models\Product;

class NonCurrentAssetsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    //function to display the non current assets all the non current assets from the assets table where the category is either property, plant and equipment or intangible assets or investment assets or vehicle assets and the current amount is greater than 0
    public function getNonCurrentAssets()
    {
        //get the non current assets from the assets table
        $getInvestmentAssets = $this->getInvestmentAssets();
        $getPropertyAssets = $this->getPropertyAssets();
        $getVehicleAssets = $this->getVehicleAssets();
        $getIntangibleAssets = $this->getIntangibleAssets();
        $getInventoryAssets = $this->getInventoryAssets();

        //return the non current assets
        return [
            'investment_assets' => $getInvestmentAssets,
            'property_assets' => $getPropertyAssets,
            'vehicle_assets' => $getVehicleAssets,
            'intangible_assets' => $getIntangibleAssets,
            'inventory_assets' => $getInventoryAssets,
        ];
        
    }


    //function to get the investment assets from the assets table
    protected function getInvestmentAssets()
    {
        //get the investment assets from the assets table
        $investmentAssets = CreateAssets::where('category', 'investment')
            ->where('current_amount', '>', 0)
            ->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->name,
                    'amount' => $asset->current_amount,
                ];
            });

        return $investmentAssets;
    }

    //function to get the property, plant and equipment assets from the assets table
    protected function getPropertyAssets()
    {
        //get the property, plant and equipment assets from the assets table
        $ppeAssets = CreateAssets::where('category', 'Property Assets')
            ->where('current_amount', '>', 0)
            ->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->name,
                    'amount' => $asset->current_amount,
                ];
            });

        return $ppeAssets;
    }

    //function to get the vehicles assets from the assets table
    protected function getVehicleAssets()
    {
        //get the vehicles assets from the assets table
        $vehicleAssets = CreateAssets::where('category', 'Vehicle Assets')
            ->where('current_amount', '>', 0)
            ->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->name,
                    'amount' => $asset->current_amount,
                ];
            });

        return $vehicleAssets;
    }

    //function to get the intangible assets from the assets table
    protected function getIntangibleAssets()
    {
        //get the intangible assets from the assets table
        $intangibleAssets = CreateAssets::where('category', 'Intangible Assets')
            ->where('current_amount', '>', 0)
            ->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->name,
                    'amount' => $asset->current_amount,
                ];
            });

        return $intangibleAssets;
    }

    //function to get the inventory assets from the assets table
    protected function getInventoryAssets()
    {
        //get the inventory assets from the assets table wher stock is greater than 0
        $inventoryAssets = Product::where('stock', '>', 0)
            ->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->name,
                    'amount' => $asset->current_amount,
                ];
            });

        return $inventoryAssets;
        
    }

    


}
