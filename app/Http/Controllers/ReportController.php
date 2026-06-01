<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Expense;
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
        $selectedCompanyId = $request->integer('company_id');

        if (! $canSeeAllCompanies) {
            $selectedScope = 'company';
            $selectedCompanyId = (int) ($user?->company_id ?? 0);
        }

        if ($selectedScope === 'company' && ! $selectedCompanyId) {
            $selectedCompanyId = (int) ($companies->first()->id ?? 0);
        }

        $expensesQuery = Expense::query()
            ->with(['company', 'department', 'creator', 'approver', 'issuer', 'checker'])
            ->orderByDesc('expense_date')
            ->orderByDesc('id');

        if ($selectedScope === 'company' && $selectedCompanyId) {
            $expensesQuery->where('company_id', $selectedCompanyId);
        } elseif (! $canSeeAllCompanies && $user) {
            $expensesQuery->where('company_id', $user->company_id);
        }

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

        $companyBreakdownQuery = Expense::query()
            ->selectRaw('company_id, COUNT(*) as expense_count, COALESCE(SUM(amount), 0) as gross_amount, COALESCE(SUM(vat_amount), 0) as vat_amount, COALESCE(SUM(net_amount), 0) as net_amount')
            ->with('company:id,name');

        if ($selectedScope === 'company' && $selectedCompanyId) {
            $companyBreakdownQuery->where('company_id', $selectedCompanyId);
        } elseif (! $canSeeAllCompanies && $user) {
            $companyBreakdownQuery->where('company_id', $user->company_id);
        }

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

        $selectedCompanyName = $selectedScope === 'company'
            ? ($companies->firstWhere('id', $selectedCompanyId)?->name ?? 'Selected Company')
            : 'All Companies';

        return view('reports', [
            'companies' => $companies,
            'selectedScope' => $selectedScope,
            'selectedCompanyId' => $selectedCompanyId,
            'selectedCompanyName' => $selectedCompanyName,
            'canSeeAllCompanies' => $canSeeAllCompanies,
            'expenseRows' => $expenseRows,
            'companyBreakdown' => $companyBreakdown,
            'totals' => $totals,
        ]);
    }
}