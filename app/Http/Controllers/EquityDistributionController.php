<?php

namespace App\Http\Controllers;

use App\Models\EquityDistribution;
use Illuminate\Http\Request;

class EquityDistributionController extends Controller
{
    //function to store add equity data in the database
    public function store(Request $request)
    {

        //dump the datas
        //dd($request->all());

        // Validate the request data
        $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'shareholder' => 'required|string',
            'shares' => 'required|integer',
            'share_value' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        // Save the data to the equity_distributions table
        EquityDistribution::create([
            'company_id' => $request->company_id,
            'shareholder' => $request->shareholder,
            'shares' => $request->shares,
            'value_held' => $request->share_value,
            'notes' => $request->notes,
        ]);

        //redirct back with success message
        return redirect()->back()->with('success', 'Equity distribution created successfully');
    }
}
