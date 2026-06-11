<?php

namespace App\Http\Controllers;

use App\Models\ItemsCategory;
use Illuminate\Http\Request;

class ItemsCategoryController extends Controller
{
    //function to store the new item category in the database
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ItemsCategory::create($request->only('category_name', 'description'));

        return redirect()->back()->with('success', 'Item category added successfully');
    }
}
