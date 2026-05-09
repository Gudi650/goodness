<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    // display the datas of the inventory pages here
    public function index()
    {
        // get the company of the user of if admin from the session
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->role?->name === 'Admin';
        $activeCompanyId = session('active_company_id');

        $companies = $isAdmin
            ? Company::query()->orderBy('name', 'asc')->get()
            : collect($currentUser?->company ? [$currentUser->company] : []);

        $usersQuery = User::with('role', 'company', 'department');

        if ($isAdmin) {
            if (! empty($activeCompanyId)) {
                $usersQuery->where('company_id', $activeCompanyId);
            }
        } else {
            $usersQuery->where('company_id', $currentUser?->company_id);
        }

        // now query the products using the selected company
        $products = Product::where(function ($query) use ($activeCompanyId, $isAdmin, $currentUser) {
            if ($isAdmin) {
                if (! empty($activeCompanyId)) {
                    $query->where('company_id', $activeCompanyId);
                }
            } else {
                $query->where('company_id', $currentUser?->company_id);
            }
        })->latest()->paginate(25);

    }
}
