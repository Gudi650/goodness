<?php

namespace App\Http\Controllers;

use App\Models\SharesDefinitions;
use Illuminate\Http\Request;

class SharesDefinitionsController extends Controller
{
    //function to store the company shares defintion as well here
    public function store(Request $request)
    {

        //dup the request data and validate it
        dd($request->all());
        
        $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'authorized_shares' => 'required|integer',
            'issued_shares' => 'nullable|integer',
            'remaining_shares' => 'nullable|integer',
            'share_value' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        //finding the remaining shares by subtracting the issued shares from the authorized shares
        $remainingShares = $request->authorized_shares - $request->issued_shares;


        //now save the data to the shares_definitions table
        SharesDefinitions::create([
            'company_id' => $request->company_id,
            'authorized_shares' => $request->authorized_shares,
            'issued_shares' => $request->issued_shares,
            'remaining_shares' => $remainingShares,
            'share_value' => $request->share_value,
            'notes' => $request->notes,
        ]);

        //redirect back with success message
        return redirect()->back()->with('success', 'Shares definition created successfully');

    }

    //function to update the shares definition
    public function update(Request $request, $id)
    {
    }
}
