<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DividendsController extends Controller
{
    //function to get the datas needed for dividends calculations
    public function getDividendsData(Request $request)
    {
        // Validate the request data
        $request->validate([
            'investment_amount' => 'required|numeric|min:0',
            'dividend_yield' => 'required|numeric|min:0',
            'holding_period' => 'required|integer|min:1',
        ]);

        // Retrieve the validated data
        $investmentAmount = $request->input('investment_amount');
        $dividendYield = $request->input('dividend_yield');
        $holdingPeriod = $request->input('holding_period');

        // Calculate the total dividends
        $totalDividends = ($investmentAmount * ($dividendYield / 100)) * $holdingPeriod;

        // Return the result as a JSON response
        return response()->json([
            'total_dividends' => round($totalDividends, 2),
            'investment_amount' => $investmentAmount,
            'dividend_yield' => $dividendYield,
            'holding_period' => $holdingPeriod,
        ]);
    }
    
}
