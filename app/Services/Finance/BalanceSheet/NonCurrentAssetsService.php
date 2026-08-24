<?php

namespace App\Services\Finance\BalanceSheet;

use App\Models\CreateAssets;
use App\Models\Product;
use App\Support\ReportFilters;

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
        //$getInventoryAssets = $this->getInventoryAssets();
        //$getOtherAssets = $this->getAssets();

        //return the non current assets
        return [
            'investment_assets' => $getInvestmentAssets,
            'property_assets' => $getPropertyAssets,
            'vehicle_assets' => $getVehicleAssets,
            'intangible_assets' => $getIntangibleAssets,
            //'inventory_assets' => $getInventoryAssets,
            //'other_assets' => $getOtherAssets,
        ];
        
    }


    //function to get the investment assets from the assets table
    protected function getInvestmentAssets()
    {
        //get the investment assets from the assets table
        $query = CreateAssets::whereHas('category', function ($query) {
            $query->where('category', 'Investment Assets');
        })
            ->where('current_value', '>', 0);
        ReportFilters::current()->applyCompany($query);
        $investmentAssets = $query->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->name,
                    'amount' => $asset->original_value,
                    'type' => 'dr', // Assuming assets are debit entries
                ];
            });

        return $investmentAssets;
    }

    //function to get the property, plant and equipment assets from the assets table
    protected function getPropertyAssets()
    {
        //get the property, plant and equipment assets from the assets table
        $query = CreateAssets::whereHas('category', function ($query) {
            $query->where('category', 'Property Assets');
        })
            ->where('current_value', '>', 0);
        ReportFilters::current()->applyCompany($query);
        $ppeAssets = $query->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->name,
                    'amount' => $asset->original_value,
                    'type' => 'dr', // Assuming assets are debit entries
                ];
            });

        return $ppeAssets;
    }

    //function to get the vehicles assets from the assets table
    protected function getVehicleAssets()
    {
        //get the vehicles assets from the assets table
        $query = CreateAssets::whereHas('category', function ($query) {
            $query->where('category', 'Vehicle Assets');
        })
            ->where('current_value', '>', 0);
        ReportFilters::current()->applyCompany($query);
        $vehicleAssets = $query->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->name,
                    'amount' => $asset->original_value,
                    'type' => 'dr', // Assuming assets are debit entries
                ];
            });

        return $vehicleAssets;
    }

    //function to get the intangible assets from the assets table
    protected function getIntangibleAssets()
    {
        //get the intangible assets from the assets table
        $query = CreateAssets::whereHas('category', function ($query) {
            $query->where('category', 'Intangible Assets');
        })
            ->where('current_value', '>', 0);
        ReportFilters::current()->applyCompany($query);
        $intangibleAssets = $query->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->name,
                    'amount' => $asset->original_value,
                    'type' => 'dr', // Assuming assets are debit entries
                ];
            });

        return $intangibleAssets;
    }

    /*function to get the inventory assets from the assets table
    protected function getInventoryAssets()
    {
        //get the inventory assets from the assets table wher stock is greater than 0
        $query = Product::where('stock', '>', 0);
        ReportFilters::current()->applyCompany($query);
        $inventoryAssets = $query->get()
            ->map(function ($asset) {
                return [
                    'name' => $asset->name,
                    'amount' => $asset->current_value,
                    'type' => 'dr', // Assuming assets are debit entries
                ];
            });

        return $inventoryAssets;
        
    } */

        protected function getAssets()
    {
        //get the vehicles assets from the assets table
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
                    'type' => 'dr', // Assuming assets are debit entries
                ];
            })
            ->groupBy('name');

        return $otherAssets;
    }
    
}
