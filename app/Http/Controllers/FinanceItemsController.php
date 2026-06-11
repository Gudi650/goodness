<?php

namespace App\Http\Controllers;

use App\Models\FinanceItems;
use Illuminate\Http\Request;

class FinanceItemsController extends Controller
{
    //function to store the new item in the database
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:items_categories,id',
        ]);

        //dd($request->all());

        //create the new item in the db
        FinanceItems::create($request->only('item_name', 'description', 'category_id'));

        //redirect back with success message
        return redirect()->back()->with('success', 'Item added successfully');
    }

    //display the items page with the list of items and categories
    public function index()
    {        
        $items = FinanceItems::with('category')->get();

        return view('finance.items', [
            'items' => $items,
        ]);
    }
}
