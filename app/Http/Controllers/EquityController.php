<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Dividends;
use App\Models\EquityDistribution;
use App\Models\SharePremuims;
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

        //get dividends data from the db and pass it to the view
        $dividendsData = $this->getDividendsData();

        //get sharepremiums from the db and pass to thw view
        $sharePremiumsData = $this->getSharePremiumsData();

        return view('equity', compact(
            
            'companies', 
            'equityData', 
            'sharesDefinitions', 
            'dividendsData',
            'sharePremiumsData',

            ));
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

    //function to get the dividends datas
    public function getDividendsData()
    {
        $dividendsData = Dividends::with('company')->with('distributions')->get();

        return $dividendsData;
    }


    //function to get the share premiums data
    protected function getSharePremiumsData()
    {
        $sharePremiumsData = SharePremuims::with('company')->get();

        return $sharePremiumsData;
    }

}
