<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Support\ReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ExpensesReport extends Controller
{
    public function previewPdf(Request $request)
    {
        return view('reports.expenses', $this->reportData($request) + ['showActions' => true]);
    }

    public function downloadPdf(Request $request)
    {
        $data = $this->reportData($request) + ['showActions' => false];

        return Pdf::loadView('reports.expenses', $data)
            ->setPaper('a4', 'portrait')
            ->download('expense_report.pdf');
    }

    protected function reportData(Request $request): array
    {
        $expenses = $this->resolveExpenses($request);

        return [
            'expenses' => $expenses,
            'reportCompanyName' => ReportFilters::current()->displayCompanyName(),
            'periodLabel' => $this->periodLabel(),
            'totals' => [
                'count' => $expenses->count(),
                'gross' => (float) $expenses->sum('amount'),
                'vat' => (float) $expenses->sum('vat_amount'),
                'net' => (float) $expenses->sum('net_amount'),
            ],
        ];
    }

    protected function resolveExpenses(Request $request): Collection
    {
        ReportFilters::boot($request);

        $query = Expense::query()
            ->with(['company', 'department', 'creator', 'checker', 'approver', 'issuer', 'bankAccount', 'financeItem']);

        ReportFilters::current()->apply($query, 'expense_date');

        if ($request->integer('expense_id')) {
            $query->whereKey($request->integer('expense_id'));
        }

        return $query
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->get();
    }

    protected function periodLabel(): string
    {
        [$start, $end] = ReportFilters::current()->dateBounds();

        if ($start && $end) {
            return $start === $end
                ? $start
                : $start.' to '.$end;
        }

        return now()->format('Y-m-d');
    }
}
