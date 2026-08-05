<?php

namespace App\Http\Controllers;

use App\Models\AssetRevaluation;
use App\Models\CreateAssets;
use App\Models\VirtualAccounts;
use Illuminate\Http\Request;

class CreateAssetsController extends Controller
{
    //function to store the asset
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'company_id' => 'nullable|exists:companies,id',
            'category_id' => 'nullable|exists:assets_categories,id',
            'type' => 'required',
            'term' => 'required|in:Short-term,Long-term',
            'original_value' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'depreciation_value' => 'nullable|numeric',
            'acquired' => 'nullable|date',
            'status' => 'required|in:Active,Disposed,Sold,Written Off',
            'account_id' => 'required|exists:virtual_accounts,id',
        ]);

        //get the generated asset code
        $validatedData['code'] = $this->generateAssetCode();

        //check which value is given then use that value, if both are given, use the current value
        $validatedData['current_value'] = $this->getAssetValue($validatedData['current_value'] ?? null, $validatedData['original_value'] ?? null);

        //check the account and deduct the money from the account if the account has enough money to create the asset
        $this->deductAccountMoneyonAssetCreation($validatedData['account_id'], $validatedData['current_value']);

        //dump the validated datas
        //dd($validatedData);


        $asset = CreateAssets::create($validatedData);

        return redirect()->back()->with('success', 'Asset created successfully');

    }



    /**
     * function to generate the asset code
     * The code is generated based on the current timestamp and a random string to ensure uniqueness.
     * then test to see if already exists in the database, if it does, generate a new one until we get a unique code.
     */
    protected function generateAssetCode()
    {
        do {
            $code = 'ASSET-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6)) . '-' . time();
        } while (CreateAssets::where('code', $code)->exists());

        return $code;
    }



    /**
     * function to do revaluation of an asset
     * This function will take the asset id, the revalued amount, and the reason for revaluation, and will update the asset's current value and create a new record in the revaluation table.
     * 
     */
    public function revaluate(CreateAssets $asset, Request $request)
    {
        //dd($request->all());

        $validatedData = $request->validate([
            'revalued_amount' => 'required|numeric',
            'surplus' => 'required|numeric',
            'notes' => 'nullable|string',
            'date_of_revaluation' => 'nullable|date',

        ]);

        //create a new record in the revaluation table
        AssetRevaluation::create([
            'revalued_amount' => $validatedData['revalued_amount'],
            'notes' => $validatedData['notes'] ?? null,
            'company_id' => $asset->company_id,
            'asset_id' => $asset->id,
            'book_value' => $asset->current_value,
            'surplus' => $validatedData['surplus'],
            'date_of_revaluation' => $validatedData['date_of_revaluation'] ?? null,
        ]);

        //update the asset's current value
        $asset->current_value = $validatedData['revalued_amount'];
        $asset->save();

        return redirect()->back()->with('success', 'Asset revaluated successfully');
    }

    //public return the asset details for the given id in json
    public function show(CreateAssets $asset)
    {
        $asset->load('company', 'category');

        return response()->json($asset);
    }

    //function to update the  account money when creating the asset
    protected function deductAccountMoneyonAssetCreation($accountId, $amount)
    {
        $account = VirtualAccounts::find($accountId);

        //check if the account has enough money to create the asset
        if($this->checkAccountMoney($accountId, $amount)) {
            $account->balance -= $amount;
            $account->save();
        } else {
            //throw an exception if the account does not have enough money
            throw new \Exception('Insufficient funds in the account to create the asset.');
        }

    }

    //function to check if the account money is enough to create the asset
    protected function checkAccountMoney($accountId, $amount)
    {
        $account = VirtualAccounts::find($accountId);

        if ($account) {
            return $account->balance >= $amount;
        }

        return false;
    }

    //check if the asset has current value or original value then decide
    //if the asset has current value, use that, else use the original value
    protected function getAssetValue($currentValue, $originalValue)
    {
        return $currentValue ?? $originalValue;
    }

}
