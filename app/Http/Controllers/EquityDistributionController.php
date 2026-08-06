<?php

namespace App\Http\Controllers;

use App\Models\EquityDistribution;
use App\Models\VirtualAccounts;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EquityDistributionController extends Controller
{
    //function to store add equity data in the database
    public function store(Request $request)
    {

        //dump the datas
        //dd($request->all());

        // Validate the request data
        $validatedData = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'shareholder' => 'required|string',
            'shares' => 'required|integer',
            'share_value' => 'required|numeric',
            'ownership' => 'required|numeric',
            'notes' => 'nullable|string',
            'virtual_account_id' => 'required|exists:virtual_accounts,id',
        ]);

        //dd($validatedData);

        //call the function to add money to the bank account in response to the amount
        $this->addMoneyToBankAccount($validatedData['virtual_account_id'], $validatedData['share_value']);

        // Save the data to the equity_distributions table
        EquityDistribution::create([
            'company_id' => $request->company_id,
            'shareholder' => $request->shareholder,
            'shares' => $request->shares,
            'value_held' => $request->share_value,
            'notes' => $request->notes,
            'ownership_percentage' => $request->ownership,
        ]);

        //redirct back with success message
        return redirect()->back()->with('success', 'Equity distribution created successfully');
    }

    //add money to the bank account in response to the amount 
    protected function addMoneyToBankAccount($accountId, $amount)
    {
        
        // Find the bank account by ID
        $bankAccount = VirtualAccounts::find($accountId);

        if (!$bankAccount) {
            throw ValidationException::withMessages([
                'account_id' => 'The account does not have enough money to create the asset.',
            ]);
        }

        // Add the amount to the bank account balance
        $bankAccount->balance += $amount;
        $bankAccount->save();

    }

}
