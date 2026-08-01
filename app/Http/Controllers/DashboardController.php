<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Leave;
use App\Models\Product;
use App\Models\Payment;
use App\Services\AccessControlService;
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

        //get the always allowed status from the service class
        $isAlwaysAllowed = app(AccessControlService::class)->isAlwaysAllowed($context[0]);

        return view('dashboard', array_merge(
            $this->companyMetrics(...$context),
            $this->employeeMetrics(...$context),
            $this->invoiceMetrics(...$context),
            $this->expenseMetrics(...$context),
            $this->expenseReviewReminderMetrics(...$context),
            $this->leaveMetrics(...$context),
            $this->inventoryMetrics(...$context),
            $this->recentTransactionMetrics(...$context),
        ));
    }

    /// Helper methods to build the dashboard context and metrics
    private function dashboardContext(): array
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser?->role?->name === 'Admin';
        $isCEO = $currentUser?->role?->name === 'CEO';
        $isAccountant = $currentUser?->role?->name === 'Accountant';
        $activeCompanyId = session('active_company_id');

        return [$currentUser, $isAdmin, $activeCompanyId, $isCEO, $isAccountant];
    }

    private function applyCompanyScope($query, $currentUser, $activeCompanyId, $isAlwaysAllowed)
    {
        if ($isAlwaysAllowed) {
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

    private function invoiceMetrics($currentUser, $activeCompanyId, $isAlwaysAllowed): array
    {
        $invoiceQuery = $this->applyCompanyScope(Invoice::query(), $currentUser, $activeCompanyId, $isAlwaysAllowed);

        return [
            'totalInvoices' => $invoiceQuery->get()->count(),
            'pendingInvoices' => (clone $this->applyCompanyScope(Invoice::query(), $currentUser, $activeCompanyId, $isAlwaysAllowed))
                ->where('status', 'pending')
                ->get()
                ->count(),
            'totalInvoiceAmount' => (clone $this->applyCompanyScope(Invoice::query(), $currentUser, $activeCompanyId, $isAlwaysAllowed))
                ->sum('total_amount') ?? 0,
        ];
    }

    private function expenseMetrics($currentUser, $activeCompanyId, $isAlwaysAllowed): array
    {
        $expenseQuery = $this->applyCompanyScope(Expense::query(), $currentUser, $activeCompanyId, $isAlwaysAllowed);

        return [
            'totalExpenses' => $expenseQuery->get()->count(),
            'pendingExpenses' => (clone $this->applyCompanyScope(Expense::query(), $currentUser, $activeCompanyId, $isAlwaysAllowed))
                ->where('status', 'submitted')
                ->get()
                ->count(),
            'approvedExpenses' => (clone $this->applyCompanyScope(Expense::query(), $currentUser, $activeCompanyId, $isAlwaysAllowed))
                ->where('status', 'approved')
                ->get()
                ->count(),
            'totalExpenseAmount' => (clone $this->applyCompanyScope(Expense::query(), $currentUser, $activeCompanyId, $isAlwaysAllowed))
                ->sum('net_amount') ?? 0,
        ];
    }

    private function expenseReviewReminderMetrics($currentUser, $isAlwaysAllowed, $activeCompanyId): array
    {
        $pendingReviewQuery = Expense::query()
            ->where('status', 'issued')
            ->whereNull('reviewed_at');

        if (! empty($currentUser?->company_id)) {
            $pendingReviewQuery->where('company_id', $currentUser->company_id);
        }

        if (! empty($currentUser?->id)) {
            $pendingReviewQuery->where('created_by', $currentUser->id);
        }

        $pendingReviewExpense = (clone $pendingReviewQuery)
            ->orderByDesc('created_at')
            ->first(['id']);

        return [
            'pendingReviewCount' => (clone $pendingReviewQuery)->count(),
            'firstPendingReviewExpenseId' => $pendingReviewExpense?->id,
        ];
    }

    private function leaveMetrics($currentUser  , $activeCompanyId,$isAlwaysAllowed): array
    {
        $pendingLeavesQuery = Leave::query()
            ->when(!$isAlwaysAllowed && !empty($currentUser?->company_id), fn($query) => $query->whereHas('user', fn($userQuery) => $userQuery->where('company_id', $currentUser->company_id)));

        $approvedLeavesQuery = Leave::query()
            ->when(!$isAlwaysAllowed && !empty($currentUser?->company_id), fn($query) => $query->whereHas('user', fn($userQuery) => $userQuery->where('company_id', $currentUser->company_id)));

        return [
            'pendingLeaves' => (clone $pendingLeavesQuery)->where('status', 'pending')->get()->count(),
            'approvedLeaves' => (clone $approvedLeavesQuery)->where('status', 'approved')->get()->count(),
        ];
    }

    private function inventoryMetrics($currentUser, $activeCompanyId, $isAlwaysAllowed): array
    {
        $lowStockQuery = $this->applyCompanyScope(Product::query(), $currentUser, $activeCompanyId, $isAlwaysAllowed)
            ->whereColumn('stock', '<=', 'reorder_level');

        return [
            'lowStockItems' => $lowStockQuery->get()->count(),
        ];
    }

    private function recentTransactionMetrics($currentUser, $isAlwaysAllowed, $activeCompanyId): array
    {
        $recentInvoices = $this->applyCompanyScope(Invoice::query(), $currentUser, $activeCompanyId, $isAlwaysAllowed)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'invoice_number', 'total_amount', 'status', 'created_at']);

        $recentPaymentsQuery = Payment::query();

        $paymentCompanyId = $this->resolvePaymentCompanyId($currentUser, $activeCompanyId, $isAlwaysAllowed);

        if ($paymentCompanyId) {
            $recentPaymentsQuery->where('company_id', $paymentCompanyId);
        }

        $recentPayments = $recentPaymentsQuery
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'payment_reference', 'amount', 'payment_status', 'created_at']);

        return [
            'recentInvoices' => $recentInvoices,
            'recentPayments' => $recentPayments,
        ];
    }

    /*
    private function resolvePaymentCompanyName($currentUser, $activeCompanyId, bool $isAdmin, bool $isCEO, bool $isAccountant): ?string
    {
        if ($isAdmin || $isCEO || $isAccountant) {
            if (! empty($activeCompanyId)) {
                return Company::query()->whereKey($activeCompanyId)->value('name');
            }

            return null;
        }

        return $currentUser?->company?->name;
    } */

    private function resolvePaymentCompanyId($currentUser, $activeCompanyId, bool $isAlwaysAllowed): ?int
    {
        if ($isAlwaysAllowed) {
            if (! empty($activeCompanyId)) {
                return (int) $activeCompanyId;
            }

            return null;
        }

        return $currentUser?->company_id;
    }
        
}
