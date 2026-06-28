<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EquityDistribution;
use Illuminate\Http\Request;

class EquityController extends Controller
{
    //function to display the equity page
    public function index()
    {

        $companies = $this->getCompanies();

        //get all equity distribution data from the db and pass it to the view
        $equityData = $this->getEquityData();

        return view('equity', compact('companies', 'equityData'));
    }


    //function to get the all companies
    protected function getCompanies()
    {
        $companies = Company::all();
        return $companies;
    }

    //function to get the equity datas from the db 
    protected function getEquityData()
    {
        $equityData = EquityDistribution::with('company')->get();

        return $equityData;
    }


}
