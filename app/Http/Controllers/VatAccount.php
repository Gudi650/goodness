<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VatAccount extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $companyId = session('active_company_id') ?: $user?->company_id;
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $invoiceQuery = Invoice::query()
            //->whereBetween('invoice_date', [$start, $end])
            //->when($companyId, fn ($q) => $q->where('company_id', $companyId));
            ->where('tax_amount', '>', 0);

        $expenseQuery = Expense::query()
            ->where('vat_amount', '>', 0);
            //->whereBetween('expense_date', [$start, $end])
            //->when($companyId, fn ($q) => $q->where('company_id', $companyId));

        $outputRows = $invoiceQuery->latest('invoice_date')->get()->map(fn ($invoice) => [
            'ref_no' => $invoice->invoice_number,
            'name' => $invoice->client_name,
            'taxable_value' => (float) $invoice->subtotal,
            'vat_rate' => $invoice->subtotal > 0 ? round(((float) $invoice->tax_amount / (float) $invoice->subtotal) * 100, 2) : 0,
            'vat_amount' => (float) $invoice->tax_amount,
            'date' => Carbon::parse($invoice->invoice_date)->format('Y-m-d'),
            'status' => $invoice->status,
            'status_class' => match ($invoice->status) {
                'paid' => 'bg-green-100 text-green-700 border-green-200',
                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                'overdue' => 'bg-red-100 text-red-700 border-red-200',
                default => 'bg-slate-100 text-slate-700 border-slate-200',
            },
            'type' => 'out',
        ])->values();

        $inputRows = $expenseQuery->latest('expense_date')->get()->map(fn ($expense) => [
            'ref_no' => $expense->expense_number,
            'name' => $expense->category,
            'taxable_value' => (float) $expense->amount,
            'vat_rate' => $expense->amount > 0 ? round(((float) $expense->vat_amount / (float) $expense->amount) * 100, 2) : 0,
            'vat_amount' => (float) $expense->vat_amount,
            'date' => Carbon::parse($expense->expense_date)->format('Y-m-d'),
            'status' => $expense->status,
            'status_class' => match ($expense->status) {
                'issued' => 'bg-green-100 text-green-700 border-green-200',
                'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                'submitted' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                default => 'bg-slate-100 text-slate-700 border-slate-200',
            },
            'type' => 'in',
        ])->values();

        $outputVat = (float) $outputRows->sum('vat_amount');
        $inputVat = (float) $inputRows->sum('vat_amount');

        return view('vat-account', [
            'period' => $start->format('F Y'),
            'outputVat' => $outputVat,
            'inputVat' => $inputVat,
            'vatPayable' => $outputVat - $inputVat,
            'outputRows' => $outputRows,
            'inputRows' => $inputRows,
        ]);

    
            
    }
}
