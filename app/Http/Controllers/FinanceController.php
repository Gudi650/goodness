<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

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

        $expenses = Expense::with(['company', 'department', 'creator'])
            ->latest()
            ->limit(100)
            ->get()
            ->map(function (Expense $expense) {
                $attachmentUrl = $expense->attachment_path ? asset('storage/' . $expense->attachment_path) : null;
                $attachmentIsImage = false;
                if ($expense->attachment_path) {
                    $ext = strtolower(pathinfo($expense->attachment_path, PATHINFO_EXTENSION));
                    $attachmentIsImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                }

                return [
                    'id' => $expense->id,
                    'display_id' => $expense->expense_number,
                    'expense_date' => Carbon::parse($expense->expense_date)->format('M d, Y'),
                    'company_name' => $expense->company?->name ?? '-',
                    'department_name' => $expense->department?->name ?? '-',
                    'category' => $expense->category,
                    'sub_category' => $expense->sub_category ?: '-',
                    'payment_method' => $expense->payment_method,
                    'reference_number' => $expense->reference_number ?: '-',
                    'amount' => (float) $expense->net_amount,
                    'gross_amount' => (float) $expense->amount,
                    'vat_included' => (bool) $expense->vat_included,
                    'vat_rate' => (float) $expense->vat_rate,
                    'vat_amount' => (float) $expense->vat_amount,
                    'net_amount' => (float) $expense->net_amount,
                    'status' => $expense->status,
                    'description' => $expense->notes ?: '-',
                    'notes' => $expense->notes ?: '-',
                    'creator_name' => $expense->creator?->name ?? '-',
                    'submitted_at' => $expense->submitted_at ? Carbon::parse($expense->submitted_at)->format('M d, Y h:i A') : '-',
                    'attachment_url' => $attachmentUrl,
                    'attachment_is_image' => $attachmentIsImage,
                ];
            })
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
        $originalFileName = null;
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentPath = $attachment->store('expense-attachments', 'public');
            $originalFileName = $attachment->getClientOriginalName();
        }

        $mode = $validated['submit_mode'] ?? 'submit';
        $status = $mode === 'draft' ? 'draft' : 'submitted';

        $expense = new Expense();
        $expense->forceFill([
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
            'original_file_name' => $originalFileName,
            'notes' => $validated['notes'] ?? null,
            'submitted_at' => $status === 'submitted' ? now() : null,
        ]);
        $expense->save();

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
    public function destroy(Expense $expense)
    {
        if ($expense->attachment_path) {
            Storage::disk('public')->delete($expense->attachment_path);
        }

        DB::table('expenses')->where('id', $expense->id)->delete();

        return redirect()->route('finance')->with('success', 'Expense deleted successfully.');
    }

    /**
     * Delete an expense record.
     */
    // kept for backward compatibility if needed
    public function destroyExpense(Expense $expense)
    {
        return $this->destroy($expense);
    }

    /**
     * Download the attachment file for a specific expense.
     */
    public function downloadAttachment($expenseId)
    {
        $expense = Expense::findOrFail($expenseId);

        //if there isnt any expense found of the id
        if(!$expense) return redirect()->route('finance')->with('error', 'Expense not found.');


        if (!$expense->attachment_path) {
            return redirect()->route('finance')->with('error', 'No attachment found for this expense.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download($expense->attachment_path, $expense->original_file_name ?: null);
    }
}
