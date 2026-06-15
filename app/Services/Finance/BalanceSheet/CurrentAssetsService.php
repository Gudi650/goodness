<?php

namespace App\Services\Finance\BalanceSheet;

use App\Models\Product;
use App\Models\VirtualAccounts;

class CurrentAssetsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    //function to display the current assets all the current assets from the assets table where the category is either inventories or trade receivables or cash and cash equivalents or other current assets and the current amount is greater than 0
    public function getCurrentAssets()
    {
        //get the current assets from the assets table
        $getInventoryAssets = $this->getInventoryAssets();
        //$getTradeReceivables = $this->getTradeReceivables();
        $getCashAndBankBalances = $this->getCashAndBankBalances();
        ////$getOtherCurrentAssets = $this->getOtherCurrentAssets();

        //return the current assets
        return [
            'inventory_assets' => $getInventoryAssets,
            // 'trade_receivables' => $getTradeReceivables,
            'cash_and_bank_balances' => $getCashAndBankBalances,
            // 'other_current_assets' => $getOtherCurrentAssets,
        ];
    }

    //function to get the current cash and bank balances from the assets table
    protected function getCashAndBankBalances()
    {
        //get the cash and bank balances from the virtual bank table
        $cashAndBankBalances = VirtualAccounts::where('balance', '>', 0)
            ->get();

        return $cashAndBankBalances;
    }

    //get the inventory assets from the products table
    protected function getInventoryAssets()
    {
        //get the inventory assets from the products table
        $inventoryAssets = Product::where('stock_quantity', '>', 0)
            ->get();

        return $inventoryAssets;
    }

    

}
