<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use App\Services\AccessControlService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    //
    public function index()
    {
        $currentUser = Auth::user();
        $activeCompanyId = session('active_company_id');

        //restrict access to none qualified users here and if not qualified redirect to dashboard with error message
        if (! app(AccessControlService::class)->isCeoOrAdminOrAccountant($currentUser) && ! app(AccessControlService::class)->isManager($currentUser)) {
            return redirect()->route('dashboard')->with('error', 'You do not have access to the HRM page.');
        }

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

        //get the companies all companies in the system for the dropdown filter
        $companies = Company::pluck('name', 'id');
        
        //get all departments for the dropdown filter
        $departments = Department::pluck('name', 'id');

        $productsQuery = Product::query()->with('company')
            ->when($isQualifiedUser && !empty($activeCompanyId), fn($query) => $query->where('company_id', $activeCompanyId))
            ->when(!$isQualifiedUser && $currentUser, fn($query) => $query->where('company_id', $currentUser->company_id));

        $summaryProducts = (clone $productsQuery)->get();

        $products = (clone $productsQuery)
            ->latest()
            ->paginate(25);

        $totalProducts = $summaryProducts->count();

        $totalStockValue = $summaryProducts->sum(function (Product $product) {
            return (float) $product->stock * (float) $product->selling_price;
        });

        $lowStockCount = $summaryProducts->filter(function (Product $product) {
            return (int) $product->stock <= (int) $product->reorder_level;
        })->count();
        
        $expiringSoonCount = $summaryProducts->filter(function (Product $product) {
            if (empty($product->expiry_date)) {
                return false;
            }

            $expiryDate = Carbon::parse($product->expiry_date);

            return $expiryDate->between(now(), now()->addDays(30));
        })->count();

        //fetch the suppliers details to be displayed from the suppliers table
        $suppliers = $this->getSuppliers($isQualifiedUser, $activeCompanyId,$currentUser);

        //fetch the products details to be displayed from the products table
        $purchases = $this->getProducts($isQualifiedUser, $activeCompanyId,$currentUser);


        return view('inventory', [
            'products' => $products,
            'users' => $users,
            'totalProducts' => $totalProducts,
            'totalStockValue' => $totalStockValue,
            'lowStockCount' => $lowStockCount,
            'expiringSoonCount' => $expiringSoonCount,
            'companies' => $companies,
            'departments' => $departments,
            'suppliers' => $suppliers,
            'purchases' => $purchases,
        ]);
    }

    //function to get suppliers details to be displayed from the suppliers table
    protected function getSuppliers($isQualifiedUser, $activeCompanyId,$currentUser)
    {
        $suppliers = Supplier::query()
            ->with('company')
            ->when($isQualifiedUser && !empty($activeCompanyId), fn($query) => $query->where('company_id', $activeCompanyId))
            ->when(!$isQualifiedUser && $currentUser, fn($query) => $query->where('company_id', $currentUser->company_id))
            ->latest()
            ->get();

        return $suppliers;
        
    }

    //function for getting the products orders details to be displayed from the products table
    protected function getProducts($isQualifiedUser, $activeCompanyId,$currentUser)
    {

        $purchases = PurchaseOrder::query()
            ->with('company')
            ->with('items') // Eager load items relationship
            ->when($isQualifiedUser && !empty($activeCompanyId), fn($query) => $query->where('company_id', $activeCompanyId))
            ->when(!$isQualifiedUser && $currentUser, fn($query) => $query->where('company_id', $currentUser->company_id))
            ->latest()
            ->get();

        return $purchases;

    }
}
