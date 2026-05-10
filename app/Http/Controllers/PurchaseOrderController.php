<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderController extends Controller
{
    /**
     * Get a specific purchase order for editing
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items');

        return response()->json([
            'success' => true,
            'data' => $purchaseOrder,
        ]);
    }

    private function generateItemSku(string $productName): string
    {
        $namePart = strtoupper(preg_replace('/[^A-Z0-9]+/', '-', strtoupper(trim($productName))));
        $namePart = trim(preg_replace('/-+/', '-', $namePart), '-');

        if ($namePart === '') {
            return '';
        }

        return 'POITEM-' . $namePart . '-' . random_int(100, 999);
    }

    private function normalizeItems(array $items): array
    {
        return collect($items)->map(function (array $item) {
            $productName = trim((string) ($item['product_name'] ?? ''));

            $item['sku'] = $this->generateItemSku($productName);

            return $item;
        })->all();
    }

    /**
     * Store a newly created purchase order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_date' => 'required|date',
            'expected_delivery_date' => 'required|date',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'priority_level' => 'required|in:Low,Normal,High,Urgent',
            'status' => 'required|in:Draft,Pending Approval,Approved,Ordered,Partially Received,Fully Received,Cancelled',
            'supplier_id' => 'required|exists:suppliers,id',
            'delivery_address' => 'nullable|string',
            'delivery_method' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'vat_percent' => 'nullable|numeric|min:0|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'deposit_amount' => 'nullable|numeric|min:0',
            'requested_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
            'approval_date' => 'nullable|date',
            'authorization_notes' => 'nullable|string',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'internal_notes' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.sku' => 'nullable|string',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_of_measure' => 'nullable|string',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
        ]);

        $validated['items'] = $this->normalizeItems($validated['items']);

        // Generate PO Number
        $poNumber = $this->generatePONumber();

        // Calculate totals
        $subtotal = collect($validated['items'])->sum('total_price');
        $discountAmount = $subtotal * (($validated['discount_percent'] ?? 0) / 100);
        $vatAmount = ($subtotal - $discountAmount) * (($validated['vat_percent'] ?? 0) / 100);
        $totalAmount = $subtotal - $discountAmount + $vatAmount + ($validated['shipping_cost'] ?? 0);
        $balanceDue = $totalAmount - ($validated['deposit_amount'] ?? 0);

        // Handle file upload
        $documentPath = null;
        $documentName = null;
        if ($request->hasFile('supporting_document')) {
            $file = $request->file('supporting_document');
            $documentName = $file->getClientOriginalName();
            $documentPath = $file->store('po/documents', 'public');
        }

        // Create PO
        $po = PurchaseOrder::create([
            'po_number' => $poNumber,
            'po_date' => $validated['po_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'],
            'company_id' => $validated['company_id'],
            'department_id' => $validated['department_id'] ?? null,
            'priority_level' => $validated['priority_level'],
            'status' => $validated['status'],
            'supplier_id' => $validated['supplier_id'],
            'delivery_address' => $validated['delivery_address'] ?? null,
            'delivery_method' => $validated['delivery_method'] ?? null,
            'subtotal' => $subtotal,
            'discount_percent' => $validated['discount_percent'] ?? 0,
            'discount_amount' => $discountAmount,
            'vat_percent' => $validated['vat_percent'] ?? 0,
            'vat_amount' => $vatAmount,
            'shipping_cost' => $validated['shipping_cost'] ?? 0,
            'total_amount' => $totalAmount,
            'payment_terms' => $validated['payment_terms'] ?? null,
            'payment_method' => $validated['payment_method'] ?? null,
            'deposit_amount' => $validated['deposit_amount'] ?? 0,
            'balance_due' => $balanceDue,
            'requested_by' => $validated['requested_by'] ?? null,
            'approved_by' => $validated['approved_by'] ?? null,
            'approval_date' => $validated['approval_date'] ?? null,
            'authorization_notes' => $validated['authorization_notes'] ?? null,
            'supporting_document_path' => $documentPath,
            'supporting_document_name' => $documentName,
            'internal_notes' => $validated['internal_notes'] ?? null,
            'terms_and_conditions' => $validated['terms_and_conditions'] ?? null,
        ]);

        // Create PO items
        foreach ($validated['items'] as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_name' => $item['product_name'],
                'sku' => $item['sku'],
                'quantity_ordered' => $item['quantity_ordered'],
                'unit_of_measure' => $item['unit_of_measure'] ?? null,
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Purchase Order created successfully',
                'po' => $po->load('items'),
            ], 201);
        }

        return redirect()->route('inventory')
            ->with('success', 'Purchase Order created successfully');
    }

    /**
     * Update the specified purchase order
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'po_date' => 'required|date',
            'expected_delivery_date' => 'required|date',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'priority_level' => 'required|in:Low,Normal,High,Urgent',
            'status' => 'required|in:Draft,Pending Approval,Approved,Ordered,Partially Received,Fully Received,Cancelled',
            'supplier_id' => 'required|exists:suppliers,id',
            'delivery_address' => 'nullable|string',
            'delivery_method' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'vat_percent' => 'nullable|numeric|min:0|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'deposit_amount' => 'nullable|numeric|min:0',
            'requested_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
            'approval_date' => 'nullable|date',
            'authorization_notes' => 'nullable|string',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'internal_notes' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.sku' => 'nullable|string',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_of_measure' => 'nullable|string',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
        ]);

        $validated['items'] = $this->normalizeItems($validated['items']);

        // Calculate totals
        $subtotal = collect($validated['items'])->sum('total_price');
        $discountAmount = $subtotal * (($validated['discount_percent'] ?? 0) / 100);
        $vatAmount = ($subtotal - $discountAmount) * (($validated['vat_percent'] ?? 0) / 100);
        $totalAmount = $subtotal - $discountAmount + $vatAmount + ($validated['shipping_cost'] ?? 0);
        $balanceDue = $totalAmount - ($validated['deposit_amount'] ?? 0);

        // Handle file upload if provided
        $documentPath = $purchaseOrder->supporting_document_path;
        $documentName = $purchaseOrder->supporting_document_name;
        if ($request->hasFile('supporting_document')) {
            // Delete old file if exists
            if ($purchaseOrder->supporting_document_path && Storage::disk('public')->exists($purchaseOrder->supporting_document_path)) {
                Storage::disk('public')->delete($purchaseOrder->supporting_document_path);
            }
            $file = $request->file('supporting_document');
            $documentName = $file->getClientOriginalName();
            $documentPath = $file->store('po/documents', 'public');
        }

        // Update PO
        $purchaseOrder->update([
            'po_date' => $validated['po_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'],
            'company_id' => $validated['company_id'],
            'department_id' => $validated['department_id'] ?? null,
            'priority_level' => $validated['priority_level'],
            'status' => $validated['status'],
            'supplier_id' => $validated['supplier_id'],
            'delivery_address' => $validated['delivery_address'] ?? null,
            'delivery_method' => $validated['delivery_method'] ?? null,
            'subtotal' => $subtotal,
            'discount_percent' => $validated['discount_percent'] ?? 0,
            'discount_amount' => $discountAmount,
            'vat_percent' => $validated['vat_percent'] ?? 0,
            'vat_amount' => $vatAmount,
            'shipping_cost' => $validated['shipping_cost'] ?? 0,
            'total_amount' => $totalAmount,
            'payment_terms' => $validated['payment_terms'] ?? null,
            'payment_method' => $validated['payment_method'] ?? null,
            'deposit_amount' => $validated['deposit_amount'] ?? 0,
            'balance_due' => $balanceDue,
            'requested_by' => $validated['requested_by'] ?? null,
            'approved_by' => $validated['approved_by'] ?? null,
            'approval_date' => $validated['approval_date'] ?? null,
            'authorization_notes' => $validated['authorization_notes'] ?? null,
            'supporting_document_path' => $documentPath,
            'supporting_document_name' => $documentName,
            'internal_notes' => $validated['internal_notes'] ?? null,
            'terms_and_conditions' => $validated['terms_and_conditions'] ?? null,
        ]);

        // Update PO items
        $purchaseOrder->items()->delete();
        foreach ($validated['items'] as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_name' => $item['product_name'],
                'sku' => $item['sku'],
                'quantity_ordered' => $item['quantity_ordered'],
                'unit_of_measure' => $item['unit_of_measure'] ?? null,
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Purchase Order updated successfully',
                'po' => $purchaseOrder->load('items'),
            ]);
        }

        return redirect()->route('inventory')
            ->with('success', 'Purchase Order updated successfully');
    }

    /**
     * Delete the specified purchase order
     */
    public function destroy(Request $request, PurchaseOrder $purchaseOrder)
    {
        // Delete supporting document if exists
        if ($purchaseOrder->supporting_document_path && Storage::disk('public')->exists($purchaseOrder->supporting_document_path)) {
            Storage::disk('public')->delete($purchaseOrder->supporting_document_path);
        }

        $purchaseOrder->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Purchase Order deleted successfully',
            ]);
        }

        return redirect()->back()->with('success', 'Purchase Order deleted successfully');
    }

    /**
     * Download supporting document
     */
    public function downloadAttachment(PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->supporting_document_path) {
            return back()->with('error', 'No document attached to this purchase order');
        }

        return Storage::disk('public')->download(
            $purchaseOrder->supporting_document_path,
            $purchaseOrder->supporting_document_name ?? 'document'
        );
    }

    /**
     * Generate unique PO number
     */
    private function generatePONumber()
    {
        $lastPO = PurchaseOrder::orderBy('id', 'desc')->first();
        $nextNumber = ($lastPO ? intval(substr($lastPO->po_number, 3)) : 0) + 1;
        return 'PO-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
