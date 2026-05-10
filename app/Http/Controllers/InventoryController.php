<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    //
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

        //get the companies all companies in the system for the dropdown filter
        $companies = Company::pluck('name', 'id');

        $productsQuery = Product::query()->with('company')
            ->when($isAdmin && !empty($activeCompanyId), fn($query) => $query->where('company_id', $activeCompanyId))
            ->when(!$isAdmin && $currentUser, fn($query) => $query->where('company_id', $currentUser->company_id));

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

        return view('inventory', [
            'products' => $products,
            'users' => $users,
            'totalProducts' => $totalProducts,
            'totalStockValue' => $totalStockValue,
            'lowStockCount' => $lowStockCount,
            'expiringSoonCount' => $expiringSoonCount,
            'companies' => $companies,
        ]);
    }
}
