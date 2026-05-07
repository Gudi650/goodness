<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $invoices = Invoice::with(['company', 'creator', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        //get the companies
        $companies = DB::table('companies')->pluck('name', 'id');

        // Get departments with company mapping for dependent dropdowns in modals.
        $departments = DB::table('departments')
            ->select('id', 'name', 'company_id')
            ->orderBy('name')
            ->get();

        $expenses = Expense::query()
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn (Expense $expense) => [
                'id' => $expense->expense_number,
                'category' => $expense->category,
                'amount' => (float) $expense->net_amount,
                'description' => $expense->notes ?: '-',
            ])
            ->all();
        $payments = [];

        return view('finance', [
            'invoices' => $invoices,
            'expenses' => $expenses,
            'payments' => $payments,
            'companies' => $companies,
            'departments' => $departments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Save a new expense record as draft or submitted.
     */
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'expense_number' => 'required|string|max:50|unique:expenses,expense_number',
            'expense_date' => 'required|date',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'category' => 'required|string|max:100',
            'sub_category' => 'nullable|string|max:100',
            'payment_method' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'vat_included' => 'required|boolean',
            'vat_rate' => 'nullable|numeric|min:0',
            'vat_amount' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'submit_mode' => 'nullable|in:draft,submit',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('expense-attachments', 'public');
        }

        $mode = $validated['submit_mode'] ?? 'submit';
        $status = $mode === 'draft' ? 'draft' : 'submitted';

        $expense = Expense::create([
            'expense_number' => $validated['expense_number'],
            'expense_date' => $validated['expense_date'],
            'company_id' => (int) $validated['company_id'],
            'department_id' => (int) $validated['department_id'],
            'created_by' => Auth::id(),
            'status' => $status,
            'category' => $validated['category'],
            'sub_category' => $validated['sub_category'] ?? null,
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
            'amount' => $validated['amount'],
            'vat_included' => (bool) $validated['vat_included'],
            'vat_rate' => $validated['vat_rate'] ?? 0,
            'vat_amount' => $validated['vat_amount'] ?? 0,
            'net_amount' => $validated['net_amount'],
            'attachment_path' => $attachmentPath,
            'notes' => $validated['notes'] ?? null,
            'submitted_at' => $status === 'submitted' ? now() : null,
        ]);

        return redirect()->route('finance')->with(
            'success',
            $status === 'draft'
                ? 'Expense saved as draft successfully.'
                : 'Expense submitted successfully.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
