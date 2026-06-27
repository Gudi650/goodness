<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class EquityController extends Controller
{
    //function to display the equity page
    public function index()
    {

        $companies = $this->getCompanies();
        return view('equity', compact('companies'));
    }


    //function to get the all companies
    protected function getCompanies()
    {
        $companies = Company::all();
        return $companies;
    }


}
