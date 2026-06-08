<?php

namespace App\Http\Controllers;

use App\Models\AssetsCategories;
use Illuminate\Http\Request;

class AssetsCategoriesController extends Controller
{
    //store the asset category
    public function store(Request $request)
    {

        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        AssetsCategories::create([
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Asset category added successfully');
    }
}
