<?php

namespace App\Http\Controllers;

use App\Models\VirtualAccounts;
use Illuminate\Http\Request;

class VirtualAccountsController extends Controller
{
    //function to store the virtual account data
    public function store(Request $request)
    {


        //validated datas
        $validatedData = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:virtual_accounts',
            'account_type' => 'required|string|in:savings,checking',
            'card_number' => 'nullable|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'currency' => 'required|string|size:3',
            'balance' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive'
        ]);

        //put a try and catch block to handle the error when savind datas in the db
        try {
            //create the virtual account
            VirtualAccounts::create($validatedData);

            //redirect with a sucess message
            return redirect()->back()->with('success', 'Virtual account created successfully.');

        } catch (\Throwable $th) {
            // Log the error for debugging
            \Log::error('Error creating virtual account: '.$th->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', 'Failed to create virtual account. Please try again.');
        }

        

        

    }
}
