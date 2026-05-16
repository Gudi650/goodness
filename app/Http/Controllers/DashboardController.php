<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Leave;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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

        // Fetch invoice metrics
        $invoiceBase = Invoice::query();
        if (!$isAdmin && !empty($currentUser?->company_id)) {
            $invoiceBase->where('company_id', $currentUser->company_id);
        } elseif (!empty($activeCompanyId)) {
            $invoiceBase->where('company_id', $activeCompanyId);
        }
        $totalInvoices = $invoiceBase->get()->count();
        $pendingInvoices = Invoice::query()
            ->when(!$isAdmin && !empty($currentUser?->company_id), fn($q) => $q->where('company_id', $currentUser->company_id))
            ->when(!empty($activeCompanyId) && $isAdmin, fn($q) => $q->where('company_id', $activeCompanyId))
            ->where('status', 'pending')
            ->get()->count();
        $totalInvoiceAmount = Invoice::query()
            ->when(!$isAdmin && !empty($currentUser?->company_id), fn($q) => $q->where('company_id', $currentUser->company_id))
            ->when(!empty($activeCompanyId) && $isAdmin, fn($q) => $q->where('company_id', $activeCompanyId))
            ->sum('total_amount') ?? 0;

        // Fetch expense metrics
        $expenseBase = Expense::query();
        if (!$isAdmin && !empty($currentUser?->company_id)) {
            $expenseBase->where('company_id', $currentUser->company_id);
        } elseif (!empty($activeCompanyId)) {
            $expenseBase->where('company_id', $activeCompanyId);
        }
        $totalExpenses = $expenseBase->get()->count();
        $pendingExpenses = Expense::query()
            ->when(!$isAdmin && !empty($currentUser?->company_id), fn($q) => $q->where('company_id', $currentUser->company_id))
            ->when(!empty($activeCompanyId) && $isAdmin, fn($q) => $q->where('company_id', $activeCompanyId))
            ->where('status', 'submitted')
            ->get()->count();
        $approvedExpenses = Expense::query()
            ->when(!$isAdmin && !empty($currentUser?->company_id), fn($q) => $q->where('company_id', $currentUser->company_id))
            ->when(!empty($activeCompanyId) && $isAdmin, fn($q) => $q->where('company_id', $activeCompanyId))
            ->where('status', 'approved')
            ->get()->count();
        $totalExpenseAmount = Expense::query()
            ->when(!$isAdmin && !empty($currentUser?->company_id), fn($q) => $q->where('company_id', $currentUser->company_id))
            ->when(!empty($activeCompanyId) && $isAdmin, fn($q) => $q->where('company_id', $activeCompanyId))
            ->sum('net_amount') ?? 0;

        // Fetch leave metrics
        $pendingLeaves = Leave::query()
            ->when(!$isAdmin && !empty($currentUser?->company_id), fn($q) => $q->whereHas('user', fn($u) => $u->where('company_id', $currentUser->company_id)))
            ->where('status', 'pending')
            ->get()->count();
        $approvedLeaves = Leave::query()
            ->when(!$isAdmin && !empty($currentUser?->company_id), fn($q) => $q->whereHas('user', fn($u) => $u->where('company_id', $currentUser->company_id)))
            ->where('status', 'approved')
            ->get()->count();

        // Fetch low stock products
        $lowStockItems = Product::query()
            ->where('stock', '<=', DB::raw('reorder_level'))
            ->when(!$isAdmin && !empty($currentUser?->company_id), fn($q) => $q->where('company_id', $currentUser->company_id))
            ->when(!empty($activeCompanyId) && $isAdmin, fn($q) => $q->where('company_id', $activeCompanyId))
            ->get()->count();

        // Recent transactions
        $recentInvoices = Invoice::query()
            ->when(!$isAdmin && !empty($currentUser?->company_id), fn($q) => $q->where('company_id', $currentUser->company_id))
            ->when(!empty($activeCompanyId) && $isAdmin, fn($q) => $q->where('company_id', $activeCompanyId))
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'invoice_number', 'total_amount', 'status', 'created_at']);

        $recentPayments = Payment::query()
            ->when(!$isAdmin && !empty($currentUser?->company_id), fn($q) => $q->where('company_id', $currentUser->company_id))
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'payment_reference', 'amount', 'payment_status', 'created_at']);

        return view('dashboard', [
            'totalCompanies' => $totalCompanies,
            'totalEmployees' => $totalEmployees,
            'activeUsers' => $activeUsers,
            'companies' => $companies,
            'totalInvoices' => $totalInvoices,
            'pendingInvoices' => $pendingInvoices,
            'totalInvoiceAmount' => $totalInvoiceAmount ?? 0,
            'totalExpenses' => $totalExpenses,
            'pendingExpenses' => $pendingExpenses,
            'approvedExpenses' => $approvedExpenses,
            'totalExpenseAmount' => $totalExpenseAmount ?? 0,
            'pendingLeaves' => $pendingLeaves,
            'approvedLeaves' => $approvedLeaves,
            'lowStockItems' => $lowStockItems,
            'recentInvoices' => $recentInvoices,
            'recentPayments' => $recentPayments,
        ]);
    }
}
