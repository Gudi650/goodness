<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExpensesReport extends Controller
{
    public function previewPdf(Request $request)
    {
        return view('reports.expenses', [
            'expense' => $this->resolveExpense($request),
            'showActions' => true,
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $expense = $this->resolveExpense($request);

        return Pdf::loadView('reports.expenses', [
            'expense' => $expense,
            'showActions' => false,
        ])
            ->setPaper('a4', 'portrait')
            ->download(($expense->expense_number ?: 'expense_report') . '.pdf');
    }

    protected function resolveExpense(Request $request): Expense
    {
        return Expense::query()
            ->with(['company', 'department', 'creator', 'checker', 'approver', 'issuer', 'bankAccount', 'financeItem'])
            ->when($request->integer('expense_id'), fn ($q, $id) => $q->whereKey($id))
            ->latest()
            ->firstOrFail();
    }
}
