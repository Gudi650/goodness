<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function expenses(Request $request)
    {
        $user = Auth::user();

        $isAdmin = $user && $user->role && $user->role->name === 'Admin';
        $isCEO = $user && $user->role && $user->role->name === 'CEO';
        $isAccountant = $user && $user->role && $user->role->name === 'Accountant';
        $canSeeAllCompanies = $isAdmin || $isCEO || $isAccountant;

        $companies = Company::query()
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        $selectedScope = $request->string('scope')->toString() ?: 'all';
        $reportType = $request->string('report_type')->toString() ?: 'expenses';
        $selectedCompanyId = $request->integer('company_id');
        $startDate = $request->string('start_date')->toString();
        $endDate = $request->string('end_date')->toString();
        $period = $request->string('period')->toString();

        if (! $canSeeAllCompanies) {
            $selectedScope = 'company';
            $selectedCompanyId = (int) ($user?->company_id ?? 0);
        }

        if ($selectedScope === 'company' && ! $selectedCompanyId) {
            $selectedCompanyId = (int) ($companies->first()->id ?? 0);
        }

        // --- REUSABLE DATE FILTER FUNCTION ---
        // This applies the correct filters dynamically to any model query based on its specific date column
        $applyDateFilters = function ($query, $dateColumn) use ($period, $startDate, $endDate) {
            if ($period == 'custom' || $period == 'custome') { // Handles both spellings just in case!
                if ($startDate) {
                    $query->where($dateColumn, '>=', Carbon::parse($startDate)->startOfDay());
                }
                if ($endDate) {
                    $query->where($dateColumn, '<=', Carbon::parse($endDate)->endOfDay());
                }
            } else if ($period == 'this_month') {
                $query->whereBetween($dateColumn, [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } else if ($period == 'last_month') {
                $query->whereBetween($dateColumn, [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]);
            } else if ($period == 'this_year') {
                $query->whereBetween($dateColumn, [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
            } else if ($period == 'last_year') {
                $query->whereBetween($dateColumn, [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()]);
            }
        };

        // --- 1. EXPENSES QUERY ---
        $expensesQuery = Expense::query()
            ->with(['company', 'department', 'creator', 'approver', 'issuer', 'checker'])
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($selectedScope === 'company' && $selectedCompanyId) {
            $expensesQuery->where('company_id', $selectedCompanyId);
        } elseif (! $canSeeAllCompanies && $user) {
            $expensesQuery->where('company_id', $user->company_id);
        }
        
        // Apply date filters to primary expenses list (switching to 'expense_date' matches your order priority)
        $applyDateFilters($expensesQuery, 'expense_date');

        $expenses = $expensesQuery->limit(250)->get();

        $expenseRows = $expenses->map(function (Expense $expense) {
            return [
                'expense_number' => $expense->expense_number,
                'expense_date' => $expense->expense_date ? Carbon::parse($expense->expense_date)->format('M d, Y') : '-',
                'company_name' => $expense->company?->name ?? '-',
                'department_name' => $expense->department?->name ?? '-',
                'category' => $expense->category ?: '-',
                'status' => $expense->status ?: '-',
                'gross_amount' => (float) $expense->amount,
                'vat_amount' => (float) $expense->vat_amount,
                'net_amount' => (float) $expense->net_amount,
                'reference_number' => $expense->reference_number ?: '-',
            ];
        });

        $totals = [
            'expense_count' => $expenses->count(),
            'gross_amount' => (float) $expenses->sum('amount'),
            'vat_amount' => (float) $expenses->sum('vat_amount'),
            'net_amount' => (float) $expenses->sum('net_amount'),
        ];

        // --- 2. COMPANY BREAKDOWN QUERY ---
        $companyBreakdownQuery = Expense::query()
            ->selectRaw('company_id, COUNT(*) as expense_count, COALESCE(SUM(amount), 0) as gross_amount, COALESCE(SUM(vat_amount), 0) as vat_amount, COALESCE(SUM(net_amount), 0) as net_amount')
            ->with('company:id,name');

        if ($selectedScope === 'company' && $selectedCompanyId) {
            $companyBreakdownQuery->where('company_id', $selectedCompanyId);
        } elseif (! $canSeeAllCompanies && $user) {
            $companyBreakdownQuery->where('company_id', $user->company_id);
        }
        
        // BUG FIX: Added date filters here
        $applyDateFilters($companyBreakdownQuery, 'expense_date');

        $companyBreakdown = $companyBreakdownQuery
            ->groupBy('company_id')
            ->orderBy('company_id')
            ->get()
            ->map(function ($item) {
                return [
                    'company_name' => $item->company?->name ?? '-',
                    'expense_count' => (int) $item->expense_count,
                    'gross_amount' => (float) $item->gross_amount,
                    'vat_amount' => (float) $item->vat_amount,
                    'net_amount' => (float) $item->net_amount,
                ];
            });

        // --- 3. INCOME / INVOICES REPORT ---
        $incomeRows = collect();
        $incomeTotals = ['invoice_count' => 0, 'subtotal' => 0.0, 'tax' => 0.0, 'total' => 0.0];
        $companyIncomeBreakdown = collect();

        if ($reportType === 'income') {
            $invoicesQuery = Invoice::query()
                ->with(['company', 'creator'])
                ->orderByDesc('invoice_date')
                ->orderByDesc('id');

            if ($selectedScope === 'company' && $selectedCompanyId) {
                $invoicesQuery->where('company_id', $selectedCompanyId);
            } elseif (! $canSeeAllCompanies && $user) {
                $invoicesQuery->where('company_id', $user->company_id);
            }
            
            // BUG FIX: Added date filters here
            $applyDateFilters($invoicesQuery, 'invoice_date');

            $invoices = $invoicesQuery->limit(250)->get();

            $incomeRows = $invoices->map(function (Invoice $inv) {
                return [
                    'invoice_number' => $inv->invoice_number,
                    'invoice_date' => $inv->invoice_date ? Carbon::parse($inv->invoice_date)->format('M d, Y') : '-',
                    'company_name' => $inv->company?->name ?? '-',
                    'client' => $inv->client_name ?: '-',
                    'status' => $inv->status ?: '-',
                    'subtotal' => (float) ($inv->subtotal ?? 0),
                    'tax' => (float) ($inv->tax_amount ?? 0),
                    'total' => (float) ($inv->total_amount ?? 0),
                ];
            });

            $incomeTotals = [
                'invoice_count' => $invoices->count(),
                'subtotal' => (float) $invoices->sum('subtotal'),
                'tax' => (float) $invoices->sum('tax_amount'),
                'total' => (float) $invoices->sum('total_amount'),
            ];

            $companyIncomeQuery = Invoice::query()
                ->selectRaw('company_id, COUNT(*) as invoice_count, COALESCE(SUM(subtotal),0) as subtotal, COALESCE(SUM(tax_amount),0) as tax, COALESCE(SUM(total_amount),0) as total')
                ->with('company:id,name');

            if ($selectedScope === 'company' && $selectedCompanyId) {
                $companyIncomeQuery->where('company_id', $selectedCompanyId);
            } elseif (! $canSeeAllCompanies && $user) {
                $companyIncomeQuery->where('company_id', $user->company_id);
            }
            
            // BUG FIX: Added date filters here
            $applyDateFilters($companyIncomeQuery, 'invoice_date');

            $companyIncomeBreakdown = $companyIncomeQuery->groupBy('company_id')->get()->map(function ($item) {
                return [
                    'company_name' => $item->company?->name ?? '-',
                    'invoice_count' => (int) $item->invoice_count,
                    'subtotal' => (float) $item->subtotal,
                    'tax' => (float) $item->tax,
                    'total' => (float) $item->total,
                ];
            });
        }

        // --- 4. BALANCE SHEET ---
        $paymentsQuery = Payment::query();
        if ($selectedScope === 'company' && $selectedCompanyId) {
            $paymentsQuery->where('company_id', $selectedCompanyId);
        } elseif (! $canSeeAllCompanies && $user) {
            $paymentsQuery->where('company_id', $user->company_id);
        }
        // BUG FIX: Date filter added (assumed column: 'created_at' or 'payment_date')
        $applyDateFilters($paymentsQuery, 'created_at');

        $payments = $paymentsQuery->get();
        $cashIn = $payments->filter(fn($p) => strtolower($p->payment_direction ?? '') === 'in')->sum(fn($p) => (float) ($p->amount * ($p->exchange_rate ?? 1)));
        $cashOut = $payments->filter(fn($p) => strtolower($p->payment_direction ?? '') === 'out')->sum(fn($p) => (float) ($p->amount * ($p->exchange_rate ?? 1)));
        $cash = $cashIn - $cashOut;

        $invoicesReceivableQuery = Invoice::query();
        if ($selectedScope === 'company' && $selectedCompanyId) {
            $invoicesReceivableQuery->where('company_id', $selectedCompanyId);
        } elseif (! $canSeeAllCompanies && $user) {
            $invoicesReceivableQuery->where('company_id', $user->company_id);
        }
        // BUG FIX: Date filter added
        $applyDateFilters($invoicesReceivableQuery, 'invoice_date');
        $receivables = $invoicesReceivableQuery->where('status', '!=', 'paid')->sum('total_amount');

        $expensesPayableQuery = Expense::query();
        if ($selectedScope === 'company' && $selectedCompanyId) {
            $expensesPayableQuery->where('company_id', $selectedCompanyId);
        } elseif (! $canSeeAllCompanies && $user) {
            $expensesPayableQuery->where('company_id', $user->company_id);
        }
        // BUG FIX: Date filter added
        $applyDateFilters($expensesPayableQuery, 'expense_date');
        
        $expensesForPayables = $expensesPayableQuery->get();
        $payables = $expensesForPayables->filter(fn($e) => !in_array(($e->status ?? ''), ['draft']))->sum(fn($e) => (float) ($e->net_amount ?? 0));

        $assets = (float) $cash + (float) $receivables;
        $liabilities = (float) $payables;
        $equity = $assets - $liabilities;

        $balance = [
            'cash' => $cash,
            'receivables' => (float) $receivables,
            'payables' => (float) $payables,
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
        ];

        // --- 5. PROFIT & LOSS (Remains trailing 12 months intentionally) ---
        $months = [];
        $labels = [];
        $revenueSeries = [];
        $expenseSeries = [];
        $profitSeries = [];

        $end = Carbon::now()->endOfMonth();
        $start = Carbon::now()->startOfMonth()->subMonths(11);

        $invoicesRangeQuery = Invoice::query()->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()], 'and');
        if ($selectedScope === 'company' && $selectedCompanyId) {
            $invoicesRangeQuery->where('company_id', $selectedCompanyId);
        } elseif (! $canSeeAllCompanies && $user) {
            $invoicesRangeQuery->where('company_id', $user->company_id);
        }
        $invoicesRange = $invoicesRangeQuery->get()->groupBy(fn($inv) => Carbon::parse($inv->invoice_date)->format('Y-m'));

        $expensesRangeQuery = Expense::query()->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()], 'and');
        if ($selectedScope === 'company' && $selectedCompanyId) {
            $expensesRangeQuery->where('company_id', $selectedCompanyId);
        } elseif (! $canSeeAllCompanies && $user) {
            $expensesRangeQuery->where('company_id', $user->company_id);
        }
        $expensesRange = $expensesRangeQuery->get()->groupBy(fn($e) => Carbon::parse($e->expense_date)->format('Y-m'));

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $key = $cursor->format('Y-m');
            $labels[] = $cursor->format('M Y');
            $monthRevenue = (float) ($invoicesRange[$key] ?? collect())->sum(fn($i) => (float) ($i->total_amount ?? 0));
            $monthExpense = (float) ($expensesRange[$key] ?? collect())->sum(fn($e) => (float) ($e->net_amount ?? 0));
            $revenueSeries[] = $monthRevenue;
            $expenseSeries[] = $monthExpense;
            $profitSeries[] = $monthRevenue - $monthExpense;
            $cursor->addMonth();
        }

        $selectedCompanyName = $selectedScope === 'company'
            ? ($companies->firstWhere('id', $selectedCompanyId)?->name ?? 'Selected Company')
            : 'All Companies';

        return view('reports', [
            'companies' => $companies,
            'selectedScope' => $selectedScope,
            'selectedCompanyId' => $selectedCompanyId,
            'selectedCompanyName' => $selectedCompanyName,
            'canSeeAllCompanies' => $canSeeAllCompanies,
            'reportType' => $reportType,
            'expenseRows' => $expenseRows,
            'companyBreakdown' => $companyBreakdown,
            'totals' => $totals,
            'incomeRows' => $incomeRows,
            'incomeTotals' => $incomeTotals,
            'companyIncomeBreakdown' => $companyIncomeBreakdown,
            'plLabels' => $labels,
            'plRevenue' => $revenueSeries,
            'plExpenses' => $expenseSeries,
            'plProfit' => $profitSeries,
            'balance' => $balance,
        ]);
    }
}