<?php

namespace App\Http\Controllers;

use App\Models\LiabilityCategory;
use Illuminate\Http\Request;

class LiabilityCategoryController extends Controller
{
    //function to store the liability category
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        LiabilityCategory::create([
            'category' => $request->category,
            'description' => $request->description,
        ]); 

        return redirect()->back()->with('success', 'Liability category added successfully');

    }
}
