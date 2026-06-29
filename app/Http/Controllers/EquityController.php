<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EquityDistribution;
use App\Models\SharesDefinitions;
use Illuminate\Http\Request;

class EquityController extends Controller
{
    //function to display the equity page
    public function index()
    {
        //get all companies from the db and pass it to the view
        $companies = $this->getCompanies();

        //get all equity distribution data from the db and pass it to the view
        $equityData = $this->getEquityData();

        //get all shares definitions data from the db and pass it to the view
        $sharesDefinitions = $this->getSharesDefinitions();

        return view('equity', compact('companies', 'equityData', 'sharesDefinitions'));
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

    //function to get the shares Definitions
    protected function getSharesDefinitions()
    {
        $sharesDefinitions = SharesDefinitions::with('company')->get();

        return $sharesDefinitions;
    }



}
