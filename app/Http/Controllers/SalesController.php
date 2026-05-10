<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    //


    //function to display the sales dashboard with customers, orders, and contracts
    public function index()
    {

        //get the companies for the dropdown filter
        $companes = Company::latest()->pluck('name', 'id');

        return view('sales',[
            'companies' => $companes,
        ]);
    }

    //function to retrive the compa
}
