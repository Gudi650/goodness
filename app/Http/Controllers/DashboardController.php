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
        $context = $this->dashboardContext();

        return view('dashboard', array_merge(
            $this->companyMetrics(...$context),
            $this->employeeMetrics(...$context),
            $this->invoiceMetrics(...$context),
            $this->expenseMetrics(...$context),
            $this->leaveMetrics(...$context),
            $this->inventoryMetrics(...$context),
            $this->recentTransactionMetrics(...$context),
        ));
    }

    private function dashboardContext(): array
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->role?->name === 'Admin';
        $isCEO = $currentUser?->role?->name === 'CEO';
        $isAccountant = $currentUser?->role?->name === 'Accountant';
        $activeCompanyId = session('active_company_id');

        return [$currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant];
    }

    private function applyCompanyScope($query, $currentUser, bool $isAdmin, $activeCompanyId, bool $isCEO, bool $isAccountant)
    {
        if ($isAdmin || $isCEO || $isAccountant) {
            if (!empty($activeCompanyId)) {
                $query->where('company_id', $activeCompanyId);
            }

            return $query;
        }

        if (!empty($currentUser?->company_id)) {
            $query->where('company_id', $currentUser->company_id);
        }

        return $query;
    }

    private function companyMetrics($currentUser, bool $isAdmin, $activeCompanyId, bool $isCEO, bool $isAccountant): array
    {
        $companiesQuery = Company::query()->withCount('users');

        if ($isAdmin or $isCEO or $isAccountant) {
            if (!empty($activeCompanyId)) {
                $companiesQuery->where('id', $activeCompanyId);
            }
        } elseif (!empty($currentUser?->company_id)) {
            $companiesQuery->where('id', $currentUser->company_id);
        } else {
            $companiesQuery->whereRaw('1 = 0');
        }

        return [
            'totalCompanies' => $companiesQuery->get()->count(),
            'companies' => (clone $companiesQuery)->orderByDesc('revenue')->get(),
        ];
    }

    private function employeeMetrics($currentUser, bool $isAdmin, $activeCompanyId, bool $isCEO, bool $isAccountant): array
    {
        $employeesQuery = User::with('role', 'company');

        if ($isAdmin or $isCEO or $isAccountant) {
            if (!empty($activeCompanyId)) {
                $employeesQuery->where('company_id', $activeCompanyId);
            }
        } else {
            $employeesQuery->where('company_id', $currentUser?->company_id);
        }

        $totalEmployees = $employeesQuery->get()->count();

        return [
            'totalEmployees' => $totalEmployees,
            'activeUsers' => $totalEmployees,
        ];

    }

    private function invoiceMetrics($currentUser, bool $isAdmin, $activeCompanyId, bool $isCEO, bool $isAccountant): array
    {
        $invoiceQuery = $this->applyCompanyScope(Invoice::query(), $currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant);

        return [
            'totalInvoices' => $invoiceQuery->get()->count(),
            'pendingInvoices' => (clone $this->applyCompanyScope(Invoice::query(), $currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant))
                ->where('status', 'pending')
                ->get()
                ->count(),
            'totalInvoiceAmount' => (clone $this->applyCompanyScope(Invoice::query(), $currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant))
                ->sum('total_amount') ?? 0,
        ];
    }

    private function expenseMetrics($currentUser, bool $isAdmin, $activeCompanyId, bool $isCEO, bool $isAccountant): array
    {
        $expenseQuery = $this->applyCompanyScope(Expense::query(), $currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant);

        return [
            'totalExpenses' => $expenseQuery->get()->count(),
            'pendingExpenses' => (clone $this->applyCompanyScope(Expense::query(), $currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant))
                ->where('status', 'submitted')
                ->get()
                ->count(),
            'approvedExpenses' => (clone $this->applyCompanyScope(Expense::query(), $currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant))
                ->where('status', 'approved')
                ->get()
                ->count(),
            'totalExpenseAmount' => (clone $this->applyCompanyScope(Expense::query(), $currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant))
                ->sum('net_amount') ?? 0,
        ];
    }

    private function leaveMetrics($currentUser, bool $isAdmin, $activeCompanyId, bool $isCEO, bool $isAccountant): array
    {
        $pendingLeavesQuery = Leave::query()
            ->when(!$isAdmin && !$isCEO && !$isAccountant && !empty($currentUser?->company_id), fn($query) => $query->whereHas('user', fn($userQuery) => $userQuery->where('company_id', $currentUser->company_id)));

        $approvedLeavesQuery = Leave::query()
            ->when(!$isAdmin && !$isCEO && !$isAccountant && !empty($currentUser?->company_id), fn($query) => $query->whereHas('user', fn($userQuery) => $userQuery->where('company_id', $currentUser->company_id)));

        return [
            'pendingLeaves' => (clone $pendingLeavesQuery)->where('status', 'pending')->get()->count(),
            'approvedLeaves' => (clone $approvedLeavesQuery)->where('status', 'approved')->get()->count(),
        ];
    }

    private function inventoryMetrics($currentUser, bool $isAdmin, $activeCompanyId, bool $isCEO, bool $isAccountant): array
    {
        $lowStockQuery = $this->applyCompanyScope(Product::query(), $currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant)
            ->whereColumn('stock', '<=', 'reorder_level');

        return [
            'lowStockItems' => $lowStockQuery->get()->count(),
        ];
    }

    private function recentTransactionMetrics($currentUser, bool $isAdmin, $activeCompanyId, bool $isCEO, bool $isAccountant): array
    {
        $recentInvoices = $this->applyCompanyScope(Invoice::query(), $currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'invoice_number', 'total_amount', 'status', 'created_at']);

        $recentPayments = $this->applyCompanyScope(Payment::query(), $currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'payment_reference', 'amount', 'payment_status', 'created_at']);

        return [
            'recentInvoices' => $recentInvoices,
            'recentPayments' => $recentPayments,
        ];
    }
    
}
