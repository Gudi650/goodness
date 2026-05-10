<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    //


    //function to display the sales dashboard with customers, orders, and contracts
    public function index()
    {


        $currentUser = Auth::user();
        $isAdmin = $currentUser && $currentUser->role && $currentUser->role->name === 'Admin';
        $activeCompanyId = session('active_company_id');

        $usersQuery = User::with('role', 'company', 'department');

        if ($isAdmin && !empty($activeCompanyId)) {
            $usersQuery->where('company_id', $activeCompanyId);
        } elseif ($currentUser) {
            $usersQuery->where('company_id', $currentUser->company_id);
        }

        // finalize the query
        $users = $usersQuery->get();

        //get the customers
        $customers = $this->getCustomers($isAdmin, $activeCompanyId, $currentUser);


        //get the companies for the dropdown filter
        $companes = Company::latest()->pluck('name', 'id');

        return view('sales',[
            'companies' => $companes,
            'customers' => $customers,
        ]);
    }

    //function to retrive the cusstomers based on search query and company filter
    public function getCustomers($isAdmin, $activeCompanyId,$currentUser)
    {
        
        $customersQuery = Customer::query()
            ->with('company')
            ->with('assignedSalesRep')
            ->when($isAdmin && !empty($activeCompanyId), fn($query) => $query->where('company_id', $activeCompanyId))
            ->when(!$isAdmin && $currentUser, fn($query) => $query->where('company_id', $currentUser->company_id));

        $customers = $customersQuery->latest()->get();

        return $customers;

    }



}
