<?php

namespace App\Http\Controllers;

use App\Models\BankTransactions;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\VirtualAccounts;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Store a newly created invoice in storage.
     */

    /*public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices',
            'company_id' => 'required',
            'client_name' => 'required|string',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string',
            'client_address' => 'nullable|string',
            'invoice_date' => 'required|date',
            'invoice_type' => 'required|in:income,expense',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:draft,pending,paid,overdue',
            'payment_method' => 'nullable|in:cash,bank,mobile,cheque',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_number' => 'required|integer',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
        ]);

        $resolvedCompanyId = $this->resolveCompanyId($validated['company_id']);
        if (!$resolvedCompanyId) {
            return back()->withErrors(['company_id' => 'Please select a valid company.']);
        }

        $invoice = Invoice::create([
            'invoice_number' => $validated['invoice_number'],
            'company_id' => $resolvedCompanyId,
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'],
            'client_address' => $validated['client_address'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'],
            'subtotal' => $validated['subtotal'],
            'tax_amount' => $validated['tax_amount'],
            'discount_amount' => $validated['discount_amount'],
            'total_amount' => $validated['total_amount'],
            'notes' => $validated['notes'],
            'created_by' => Auth::id(),
            'invoice_type' => $validated['invoice_type'],
        ]);

        foreach ($validated['items'] as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_number' => $item['item_number'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);
        }

        return redirect()->route('finance')->with('success', 'Invoice sent successfully! Invoice #' . $invoice->invoice_number . ' has been created.');
    } */

    /**
     * Get invoices for the finance page.
     */
    public function index()
    {
        $invoices = Invoice::with(['company', 'creator', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $companies = DB::table('companies')->pluck('name', 'id');

        $expenses = [];
        $payments = [];

        return view('finance', [
            'invoices' => $invoices,
            'expenses' => $expenses,
            'payments' => $payments,
            'companies' => $companies,
        ]);
    }

    /**
     * Get a single invoice with all its details.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['company', 'creator', 'items']);
        return response()->json($invoice);
    }

    /**
     * Download an invoice as a PDF.
     */
    public function download(Invoice $invoice)
    {
        $invoice->load(['company', 'creator', 'items']);

        $pdf = Pdf::loadView('finance.invoices-print', [
            'invoice' => $invoice,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Update an invoice.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_name' => 'required|string',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string',
            'client_address' => 'nullable|string',
            'invoice_date' => 'required|date',
            'invoice_type' => 'required|in:income,expense',
            'bank_id' => 'required|exists:virtual_accounts,id',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:draft,pending,paid,overdue',
            'payment_method' => 'nullable|in:cash,bank,mobile,cheque',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_number' => 'required|integer',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'],
            'client_address' => $validated['client_address'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'],
            'subtotal' => $validated['subtotal'],
            'tax_amount' => $validated['tax_amount'],
            'discount_amount' => $validated['discount_amount'],
            'total_amount' => $validated['total_amount'],
            'notes' => $validated['notes'],
            'invoice_type' => $validated['invoice_type'],
            'bank_id' => $validated['bank_id'],
        ]);

        $invoice->items()->delete();

        foreach ($validated['items'] as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_number' => $item['item_number'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invoice updated successfully',
        ]);
    }

    /**
     * Delete an invoice.
     */
    public function destroy(Invoice $invoice)
    {
        Invoice::query()->whereKey($invoice->id)->delete();

        return back()->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Save invoice as draft.
     */
    public function saveDraft(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices',
            'company_id' => 'required',
            'bank_id' => 'required|exists:virtual_accounts,id',
            'client_name' => 'required|string',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string',
            'client_address' => 'nullable|string',
            'invoice_date' => 'required|date',
            'invoice_type' => 'required|in:income,expense',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'payment_method' => 'nullable|in:cash,bank,mobile,cheque',
            'subtotal' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_number' => 'required|integer',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
        ]);

        $resolvedCompanyId = $this->resolveCompanyId($validated['company_id']);
        if (!$resolvedCompanyId) {
            return back()->withErrors(['company_id' => 'Please select a valid company.']);
        }

        //check if the bank submitted is of same company and also check if the bank has sufficient money as well
        if (isset($validated['bank_id']) ) {
            $bankId = $validated['bank_id'];
            $companyId = $validated['company_id'];
            $amount = $validated['total_amount'];
            $invoice_type = $validated['invoice_type'];

            if (!$this->validateBankForInvoice($bankId, $companyId, $amount, $invoice_type)) {
                return redirect()->back()->with('error', 'Invalid bank account or insufficient funds for this expense.');
            }
        }

        $invoice = Invoice::create([
            'invoice_number' => $validated['invoice_number'],
            'company_id' => $resolvedCompanyId,
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'],
            'client_address' => $validated['client_address'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'] ?? $validated['invoice_date'],
            'status' => 'draft',
            'payment_method' => $validated['payment_method'],
            'subtotal' => $validated['subtotal'],
            'tax_amount' => 0,
            'discount_amount' => $validated['discount_amount'],
            'total_amount' => $validated['total_amount'],
            'notes' => $validated['notes'],
            'created_by' => Auth::id(),
            'invoice_type' => $validated['invoice_type'],
            'bank_id' => $validated['bank_id'],
        ]);

        foreach ($validated['items'] as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_number' => $item['item_number'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);
        }

        //check if the invoice type is expense or income then either add or deduct money from the virtual account balance
        $this->updateVirtualAccountBalance($invoice);

        //now save the transaction in the transactions table
        $this->saveTransaction($invoice);
           

        return redirect()->route('finance')->with('success', 'Invoice saved as draft! Invoice #' . $invoice->invoice_number . ' is ready for editing.');
    }

    /**
     * Resolve company id from numeric id, known slug, or company name.
     */
    private function resolveCompanyId(mixed $companyInput): ?int
    {
        if (is_numeric($companyInput)) {
            $company = DB::table('companies')->where('id', (int) $companyInput)->first();
            return $company?->id;
        }

        $slugMap = [
            'goodness-tz' => 'Goodness Tanzania Ltd',
            'goodness-ke' => 'Goodness Kenya Ltd',
            'goodness-ug' => 'Goodness Uganda Ltd',
        ];

        $resolvedName = $slugMap[(string) $companyInput] ?? (string) $companyInput;
        $company = DB::table('companies')->where('name', $resolvedName)->first();

        return $company?->id;
    }

    /**
     * check if the invoice is expense or income
     */
    protected function isInvoiceIncome(Invoice $invoice): bool
    {
        return $invoice->invoice_type === 'income';
    }

    /**
     * deduct or add money from the virtual account based on the invoice type
     */
    protected function updateVirtualAccountBalance(Invoice $invoice): void
    {
        if ($this->isInvoiceIncome($invoice)) {
            // For income invoices, add the total amount to the virtual account balance
            DB::table('virtual_accounts')
                ->where('company_id', $invoice->company_id)
                ->increment('balance', $invoice->total_amount);
        } else {
            // For expense invoices, deduct the total amount from the virtual account balance
            DB::table('virtual_accounts')
                ->where('company_id', $invoice->company_id)
                ->decrement('balance', $invoice->total_amount);
        }
    }

    /**
     * save into transactions table when an invoice is created
     */
    protected function saveTransaction(Invoice $invoice): void
    {

        BankTransactions::create([
            'bank_id' => $invoice->bank_id,
            'company_id' => $invoice->company_id,
            'balance_after' => VirtualAccounts::find($invoice->bank_id)->balance,
            'affecting_balance' => -$invoice->total_amount,
            'expense_id' => $invoice->id,
            'transaction_type' => 'expense',
        ]);

    }

    /**
     * Make sure that the bank selected if in the same company selected
     */
    protected function validateBankForInvoice($bankId, $companyId, $amount, $invoice_type)
    {
        $bankAccount = VirtualAccounts::where('id', $bankId)
            ->where('company_id', $companyId)
            ->first();

        if (!$bankAccount) {
            return false; // Bank account does not belong to the company
        }

        if ($invoice_type == "expense" && $bankAccount->balance < $amount) {
            return false; 
        }

        return true; // Bank account is valid and has sufficient funds
    }

}
