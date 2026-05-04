<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController
 *
 * Handles the main dashboard view with key metrics and statistics.
 */
class DashboardController extends Controller
{
    /**
     * Show the main dashboard with stats.
     */
    public function index()
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->role?->name === 'Admin';
        $activeCompanyId = session('active_company_id');

        $companiesQuery = Company::query()->withCount('users');

        // Total Companies visible to the current user
        if ($isAdmin) {
            if (!empty($activeCompanyId)) {
                $companiesQuery->where('id', $activeCompanyId);
            } else {
                // Admins can view every registered company.
            }
        } else {
            // Non-admin users only see their own company (if registered/assigned)
            if (!empty($currentUser?->company_id)) {
                $companiesQuery->where('id', $currentUser->company_id);
            } else {
                $companiesQuery->whereRaw('1 = 0');
            }
        }

        $totalCompanies = (clone $companiesQuery)->count();
        $companies = (clone $companiesQuery)
            ->orderByDesc('revenue')
            ->get();

        // Total Employees (Users)
        $employeesQuery = User::with('role', 'company');
        if ($isAdmin) {
            if (!empty($activeCompanyId)) {
                $employeesQuery->where('company_id', $activeCompanyId);
            }
        } else {
            $employeesQuery->where('company_id', $currentUser?->company_id);
        }
        $totalEmployees = $employeesQuery->count();

        // Active Users (for now, all users are considered "active")
        $activeUsers = $employeesQuery->count();

        return view('dashboard', [
            'totalCompanies' => $totalCompanies,
            'totalEmployees' => $totalEmployees,
            'activeUsers' => $activeUsers,
            'companies' => $companies,
        ]);
    }
}
