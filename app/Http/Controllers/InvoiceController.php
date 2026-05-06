<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices',
            'company_id' => 'required',
            'client_name' => 'required|string',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string',
            'client_address' => 'nullable|string',
            'invoice_date' => 'required|date',
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

        // Create the invoice
        $resolvedCompanyId = $this->resolveCompanyId($validated['company_id']);
        if (!$resolvedCompanyId) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a valid company.',
            ], 422);
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
        ]);

        // Create invoice items
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
            'message' => 'Invoice created successfully',
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
        ]);
    }

    /**
     * Get invoices for the finance page.
     */
    public function index(Request $request)
    {
        $invoices = Invoice::with(['company', 'creator', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($invoices);
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

        // Update invoice
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
        ]);

        // Delete old items and create new ones
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
        Invoice::destroy($invoice->id);

        return response()->json([
            'success' => true,
            'message' => 'Invoice deleted successfully',
        ]);
    }

    /**
     * Save invoice as draft.
     */
    public function saveDraft(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices',
            'company_id' => 'required',
            'client_name' => 'required|string',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string',
            'client_address' => 'nullable|string',
            'invoice_date' => 'required|date',
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

        // Create invoice as draft
        $resolvedCompanyId = $this->resolveCompanyId($validated['company_id']);
        if (!$resolvedCompanyId) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a valid company.',
            ], 422);
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
        ]);

        // Create invoice items
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
            'message' => 'Invoice saved as draft',
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
        ]);
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
}
