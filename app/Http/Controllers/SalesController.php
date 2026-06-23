<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Contract;
use App\Models\Department;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Services\AccessControlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    //


    //function to display the sales dashboard with customers, orders, and contracts
    public function index()
    {


        $currentUser = Auth::user();
        $activeCompanyId = session('active_company_id');

        //get teh authorised users for the company 
        $isQualifiedUser = app(AccessControlService::class)->isCeoOrAdminOrAccountant($currentUser);

        $usersQuery = User::with('role', 'company', 'department');

        if ($isQualifiedUser && !empty($activeCompanyId)) {
            $usersQuery->where('company_id', $activeCompanyId);
        } elseif ($currentUser) {
            $usersQuery->where('company_id', $currentUser->company_id);
        }

        // finalize the query
        $users = $usersQuery->get();

        //get the customers
        $customers = $this->getCustomers($isQualifiedUser, $activeCompanyId, $currentUser);

        //get the departments
        $departments = Department::pluck('name', 'id');

        //get the orders
        $orders = $this->getOrders($isQualifiedUser, $activeCompanyId, $currentUser);

        //get the companies for the dropdown filter
        $companes = Company::orderByDesc('id')->pluck('name', 'id');

        //get suppliers for contract counterparties
        $suppliers = $this->getSuppliers($isQualifiedUser, $activeCompanyId, $currentUser);

        //get the contracts
        $contracts = $this->getContracts($isQualifiedUser, $activeCompanyId, $currentUser);

        // prepare lightweight arrays for inline JS (avoid closures in Blade @json)
        $contractCustomers = ($customers ?? collect())->map(function ($customer) {
            return [
                'name' => $customer->customer_name,
                'contact_person' => $customer->contact_person_name,
                'phone' => $customer->phone_number,
                'email' => $customer->email,
                'address' => $customer->street_address,
            ];
        })->values()->toArray();

        $contractSuppliers = ($suppliers ?? collect())->map(function ($supplier) {
            return [
                'name' => $supplier->supplier_name,
                'contact_person' => $supplier->contact_person_name,
                'phone' => $supplier->phone_number,
                'email' => $supplier->email,
                'address' => $supplier->street_address,
            ];
        })->values()->toArray();

        //get the products
        $products = Product::query()->orderByDesc('id')->get();

        return view('sales',[
            'companies' => $companes,
            'customers' => $customers,
            'departments' => $departments,
            'products' => $products,
            'users' => $users,
            'orders' => $orders,
            'suppliers' => $suppliers,
            'contracts' => $contracts,
            'contractCustomers' => $contractCustomers,
            'contractSuppliers' => $contractSuppliers,
        ]);
    }

    //function to retrive the cusstomers based on search query and company filter
    public function getCustomers($isQualifiedUser, $activeCompanyId,$currentUser)
    {
        
        $customersQuery = Customer::query()
            ->with('company')
            ->with('assignedSalesRep')
            ->when($isQualifiedUser && !empty($activeCompanyId), fn($query) => $query->where('company_id', $activeCompanyId))
            ->when(!$isQualifiedUser && $currentUser, fn($query) => $query->where('company_id', $currentUser->company_id));

        $customers = $customersQuery->latest()->get();

        return $customers;

    }

    //function to get the products based on search query and company filter
    public function getProducts($isQualifiedUser, $activeCompanyId,$currentUser)
    {
        $productsQuery = Product::query()
            ->with('company')
            ->when($isQualifiedUser && !empty($activeCompanyId), function ($query) use ($activeCompanyId) {
                $query->where(function ($productQuery) use ($activeCompanyId) {
                    $productQuery->where('company_id', $activeCompanyId)
                        ->orWhereNull('company_id');
                });
            })
            ->when(!$isQualifiedUser && $currentUser, function ($query) use ($currentUser) {
                $query->where(function ($productQuery) use ($currentUser) {
                    $productQuery->where('company_id', $currentUser->company_id)
                        ->orWhereNull('company_id');
                });
            });

        $products = $productsQuery->latest()->get();

        return $products;
    }

    public function getSuppliers($isQualifiedUser, $activeCompanyId, $currentUser)
    {
        $suppliersQuery = Supplier::query()
            ->when($isQualifiedUser && !empty($activeCompanyId), fn($query) => $query->where('company_id', $activeCompanyId))
            ->when(!$isQualifiedUser && $currentUser, fn($query) => $query->where('company_id', $currentUser->company_id));

        return $suppliersQuery->latest()->get();
    }

    public function getContracts($isQualifiedUser, $activeCompanyId, $currentUser)
    {
        $contractsQuery = Contract::query()
            ->with('company', 'manager')
            ->when($isQualifiedUser && !empty($activeCompanyId), fn($query) => $query->where('contract_our_company', $activeCompanyId))
            ->when(!$isQualifiedUser && $currentUser, fn($query) => $query->where('contract_our_company', $currentUser->company_id));

        return $contractsQuery->latest()->get();
    }

    //function get orders dta from the db
    public function getOrders($isQualifiedUser, $activeCompanyId,$currentUser)
    {
        $ordersQuery = Order::query()
            ->with('company', 'customer', 'salesRep', 'approvedBy', 'items')
            ->when($isQualifiedUser && !empty($activeCompanyId), fn($query) => $query->where('company_id', $activeCompanyId))
            ->when(!$isQualifiedUser && $currentUser, fn($query) => $query->where('company_id', $currentUser->company_id));

        $orders = $ordersQuery->latest()->get();

        return $orders;
    }



}
