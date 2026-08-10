<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Dividends;
use App\Models\EquityDistribution;
use App\Models\SharePremuims;
use App\Models\SharesDefinitions;
use App\Models\VirtualAccounts;
use Illuminate\Http\Request;

class EquityController extends Controller
{
    //function to display the equity page
    public function index()
    {
        $activeCompanyId = session('active_company_id');

        //get all companies from the db and pass it to the view
        $companies = $this->getCompanies();

        //get all equity distribution data from the db and pass it to the view
        $equityData = $this->getEquityData($activeCompanyId);

        //get all shares definitions data from the db and pass it to the view
        $sharesDefinitions = $this->getSharesDefinitions($activeCompanyId);

        //get dividends data from the db and pass it to the view
        $dividendsData = $this->getDividendsData($activeCompanyId);

        //get sharepremiums from the db and pass to thw view
        $sharePremiumsData = $this->getSharePremiumsData($activeCompanyId);

        //get the net value of the shares for every company and pass it to the view
        $netValues = $this->getNetValue($activeCompanyId);

        //get the virtual accounts with their companies and pass it to the view
        $virtualAccounts = $this->getVirtualAccounts($activeCompanyId);

        return view('equity', compact(
            
            'companies', 
            'equityData', 
            'sharesDefinitions', 
            'dividendsData',
            'sharePremiumsData',
            'netValues',
            'virtualAccounts'

            ));
    }


    //function to get the all companies
    protected function getCompanies()
    {
        $companies = Company::all();
        return $companies;
    }

    //function to get the equity datas from the db 
    protected function getEquityData($activeCompanyId = null)
    {
        $equityData = EquityDistribution::with('company')
            ->when(! empty($activeCompanyId), fn ($query) => $query->where('company_id', $activeCompanyId))
            ->get();

        return $equityData;
    }

    //function to get the shares Definitions
    protected function getSharesDefinitions($activeCompanyId = null)
    {
        $sharesDefinitions = SharesDefinitions::with('company')
            ->when(! empty($activeCompanyId), fn ($query) => $query->where('company_id', $activeCompanyId))
            ->get();

        return $sharesDefinitions;
    }

    //function to get the dividends datas
    public function getDividendsData($activeCompanyId = null)
    {
        $dividendsData = Dividends::with('company')->with('distributions')
            ->when(! empty($activeCompanyId), fn ($query) => $query->where('company_id', $activeCompanyId))
            ->get();

        return $dividendsData;
    }


    //function to get the share premiums data
    protected function getSharePremiumsData($activeCompanyId = null)
    {
        $sharePremiumsData = SharePremuims::with('company')
            ->when(! empty($activeCompanyId), fn ($query) => $query->where('company_id', $activeCompanyId))
            ->get();

        return $sharePremiumsData;
    }

    //get the net value of the shares for very company and return it as an array
    public function getNetValue($activeCompanyId = null)
    {
        $sharesDefinitions = SharesDefinitions::with('company')
            ->when(! empty($activeCompanyId), fn ($query) => $query->where('company_id', $activeCompanyId))
            ->get();

        $netValues = [];

        foreach ($sharesDefinitions as $shareDef) {
            $netValue = (float) ($shareDef->issued_shares ?? 0) * (float) ($shareDef->share_value ?? 0);
            $netValues[$shareDef->company_id] = $netValue;
        }

        return $netValues;
    }

    //get the virtual accounts with their companies as well
    public function getVirtualAccounts($activeCompanyId = null)
    {
        $virtualAccounts = VirtualAccounts::with('company')
            ->when(! empty($activeCompanyId), fn ($query) => $query->where('company_id', $activeCompanyId))
            ->get();

        return $virtualAccounts;
    }

}
