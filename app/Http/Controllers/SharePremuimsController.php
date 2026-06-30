<?php

namespace App\Http\Controllers;

use App\Models\SharePremuims;
use Illuminate\Http\Request;

class SharePremuimsController extends Controller
{
    //function to store the share premium data
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'shares_issued' => 'required|integer|min:1',
            'nominal_value' => 'required|numeric|min:0',
            'issue_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $premiumPerShare = $validated['issue_price'] - $validated['nominal_value'];
        $totalPremium = $premiumPerShare * $validated['shares_issued'];

        SharePremuims::create([
            'company_id' => $validated['company_id'],
            'shares_issued' => $validated['shares_issued'],
            'nominal_value' => $validated['nominal_value'],
            'issue_price' => $validated['issue_price'],
            'premium_per_share' => $premiumPerShare,
            'total_premium' => $totalPremium,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Share premium record created successfully.');
    }
}
