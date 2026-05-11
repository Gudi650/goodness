<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Order;
use App\Models\Product;
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

        //get the departments
        $departments = Department::pluck('name', 'id');

        //get the orders
        $orders = $this->getOrders($isAdmin, $activeCompanyId, $currentUser);

        //get the companies for the dropdown filter
        $companes = Company::orderByDesc('id')->pluck('name', 'id');

        //get the products
        $products = Product::query()->orderByDesc('id')->get();

        return view('sales',[
            'companies' => $companes,
            'customers' => $customers,
            'departments' => $departments,
            'products' => $products,
            'users' => $users,
            'orders' => $orders,
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

    //function to get the products based on search query and company filter
    public function getProducts($isAdmin, $activeCompanyId,$currentUser)
    {
        $productsQuery = Product::query()
            ->with('company')
            ->when($isAdmin && !empty($activeCompanyId), function ($query) use ($activeCompanyId) {
                $query->where(function ($productQuery) use ($activeCompanyId) {
                    $productQuery->where('company_id', $activeCompanyId)
                        ->orWhereNull('company_id');
                });
            })
            ->when(!$isAdmin && $currentUser, function ($query) use ($currentUser) {
                $query->where(function ($productQuery) use ($currentUser) {
                    $productQuery->where('company_id', $currentUser->company_id)
                        ->orWhereNull('company_id');
                });
            });

        $products = $productsQuery->latest()->get();

        return $products;
    }

    //function get orders dta from the db
    public function getOrders($isAdmin, $activeCompanyId,$currentUser)
    {
        $ordersQuery = Order::query()
            ->with('company', 'customer', 'salesRep', 'approvedBy', 'items')
            ->when($isAdmin && !empty($activeCompanyId), fn($query) => $query->where('company_id', $activeCompanyId))
            ->when(!$isAdmin && $currentUser, fn($query) => $query->where('company_id', $currentUser->company_id));

        $orders = $ordersQuery->latest()->get();

        return $orders;
    }



}
