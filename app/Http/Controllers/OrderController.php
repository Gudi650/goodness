<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'company_id' => 'nullable|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'customer_id' => 'required|exists:customers,id',
            'billing_address' => 'nullable|string|max:500',
            'shipping_address' => 'nullable|string|max:500',
            'order_type' => 'required|in:Sale,Quotation,Proforma Invoice,Return',
            'priority' => 'required|in:Normal,High,Urgent',
            'status' => 'required|in:Draft,Confirmed,Processing,Ready for Delivery,Delivered,Cancelled,Returned',
            'subtotal' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'vat_enabled' => 'nullable|boolean',
            'vat_percent' => 'nullable|numeric|min:0|max:100',
            'vat_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'other_charges' => 'nullable|numeric|min:0',
            'grand_total' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'balance_due' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:Unpaid,Partially Paid,Fully Paid',
            'payment_method' => 'nullable|string|max:100',
            'payment_reference' => 'nullable|string|max:200',
            'payment_date' => 'nullable|date',
            'credit_terms' => 'nullable|string|max:50',
            'credit_due_date' => 'nullable|date',
            'delivery_method' => 'nullable|string|max:100',
            'delivery_date' => 'nullable|date',
            'delivery_status' => 'required|in:Not Dispatched,In Transit,Delivered,Failed Delivery',
            'driver_name' => 'nullable|string|max:100',
            'vehicle_plate_number' => 'nullable|string|max:50',
            'delivery_notes' => 'nullable|string|max:1000',
            'sales_rep_id' => 'required|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
            'commission_percent' => 'nullable|numeric|min:0|max:100',
            'commission_amount' => 'nullable|numeric|min:0',
            'internal_notes' => 'nullable|string|max:2000',
            'customer_notes' => 'nullable|string|max:2000',
            'terms_and_conditions' => 'nullable|string|max:2000',
            'lpo_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            // Order Items validation
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.description' => 'nullable|string|max:200',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_of_measure' => 'required|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.line_total' => 'nullable|numeric|min:0',
        ]);

        // Generate order number
        $orderNumber = 'ORD-' . str_pad((string) ((Order::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT);

        // Handle file upload
        $lpoFilePath = null;
        $lpoFileName = null;
        if ($request->hasFile('lpo_file')) {
            $file = $request->file('lpo_file');
            $lpoFileName = $file->getClientOriginalName();
            $lpoFilePath = $file->store('orders/lpo', 'public');
        }

        // Create the order
        $order = Order::create([
            'order_number' => $orderNumber,
            'order_date' => $validated['order_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
            'company_id' => $validated['company_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'customer_id' => $validated['customer_id'],
            'billing_address' => $validated['billing_address'] ?? null,
            'shipping_address' => $validated['shipping_address'] ?? null,
            'order_type' => $validated['order_type'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'subtotal' => $validated['subtotal'] ?? 0,
            'discount_percent' => $validated['discount_percent'] ?? 0,
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'vat_enabled' => $validated['vat_enabled'] ?? true,
            'vat_percent' => $validated['vat_percent'] ?? 18,
            'vat_amount' => $validated['vat_amount'] ?? 0,
            'shipping_cost' => $validated['shipping_cost'] ?? 0,
            'other_charges' => $validated['other_charges'] ?? 0,
            'grand_total' => $validated['grand_total'] ?? 0,
            'amount_paid' => $validated['amount_paid'] ?? 0,
            'balance_due' => $validated['balance_due'] ?? 0,
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'] ?? null,
            'payment_reference' => $validated['payment_reference'] ?? null,
            'payment_date' => $validated['payment_date'] ?? null,
            'credit_terms' => $validated['credit_terms'] ?? null,
            'credit_due_date' => $validated['credit_due_date'] ?? null,
            'delivery_method' => $validated['delivery_method'] ?? null,
            'delivery_date' => $validated['delivery_date'] ?? null,
            'delivery_status' => $validated['delivery_status'],
            'driver_name' => $validated['driver_name'] ?? null,
            'vehicle_plate_number' => $validated['vehicle_plate_number'] ?? null,
            'delivery_notes' => $validated['delivery_notes'] ?? null,
            'sales_rep_id' => $validated['sales_rep_id'],
            'approved_by' => $validated['approved_by'] ?? null,
            'commission_percent' => $validated['commission_percent'] ?? 0,
            'commission_amount' => $validated['commission_amount'] ?? 0,
            'lpo_file_path' => $lpoFilePath,
            'lpo_file_name' => $lpoFileName,
            'internal_notes' => $validated['internal_notes'] ?? null,
            'customer_notes' => $validated['customer_notes'] ?? null,
            'terms_and_conditions' => $validated['terms_and_conditions'] ?? null,
        ]);

        // Create order items
        $items = $validated['items'] ?? [];
        foreach ($items as $index => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_number' => $index + 1,
                'product_id' => $item['product_id'],
                'sku' => $item['sku'] ?? null,
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_of_measure' => $item['unit_of_measure'],
                'unit_price' => $item['unit_price'],
                'discount_percent' => $item['discount_percent'] ?? 0,
                'line_total' => $item['line_total'] ?? 0,
            ]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => "Order '{$orderNumber}' created successfully.",
                'order_id' => $order->id,
                'order_number' => $orderNumber,
            ]);
        }

        return redirect()->back()->with('success', "Order '{$orderNumber}' created successfully.");
    }

    public function show(Order $order)
    {
        $order->load('customer', 'company', 'salesRep', 'approvedBy', 'items.product');
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json($order);
        }

        return redirect()->back();
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'company_id' => 'nullable|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'customer_id' => 'required|exists:customers,id',
            'billing_address' => 'nullable|string|max:500',
            'shipping_address' => 'nullable|string|max:500',
            'order_type' => 'required|in:Sale,Quotation,Proforma Invoice,Return',
            'priority' => 'required|in:Normal,High,Urgent',
            'status' => 'required|in:Draft,Confirmed,Processing,Ready for Delivery,Delivered,Cancelled,Returned',
            'subtotal' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'vat_enabled' => 'nullable|boolean',
            'vat_percent' => 'nullable|numeric|min:0|max:100',
            'vat_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'other_charges' => 'nullable|numeric|min:0',
            'grand_total' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'balance_due' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:Unpaid,Partially Paid,Fully Paid',
            'payment_method' => 'nullable|string|max:100',
            'payment_reference' => 'nullable|string|max:200',
            'payment_date' => 'nullable|date',
            'credit_terms' => 'nullable|string|max:50',
            'credit_due_date' => 'nullable|date',
            'delivery_method' => 'nullable|string|max:100',
            'delivery_date' => 'nullable|date',
            'delivery_status' => 'required|in:Not Dispatched,In Transit,Delivered,Failed Delivery',
            'driver_name' => 'nullable|string|max:100',
            'vehicle_plate_number' => 'nullable|string|max:50',
            'delivery_notes' => 'nullable|string|max:1000',
            'sales_rep_id' => 'required|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
            'commission_percent' => 'nullable|numeric|min:0|max:100',
            'commission_amount' => 'nullable|numeric|min:0',
            'internal_notes' => 'nullable|string|max:2000',
            'customer_notes' => 'nullable|string|max:2000',
            'terms_and_conditions' => 'nullable|string|max:2000',
        ]);

        $order->update([
            'order_date' => $validated['order_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
            'company_id' => $validated['company_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'customer_id' => $validated['customer_id'],
            'billing_address' => $validated['billing_address'] ?? null,
            'shipping_address' => $validated['shipping_address'] ?? null,
            'order_type' => $validated['order_type'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'subtotal' => $validated['subtotal'] ?? 0,
            'discount_percent' => $validated['discount_percent'] ?? 0,
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'vat_enabled' => $validated['vat_enabled'] ?? true,
            'vat_percent' => $validated['vat_percent'] ?? 18,
            'vat_amount' => $validated['vat_amount'] ?? 0,
            'shipping_cost' => $validated['shipping_cost'] ?? 0,
            'other_charges' => $validated['other_charges'] ?? 0,
            'grand_total' => $validated['grand_total'] ?? 0,
            'amount_paid' => $validated['amount_paid'] ?? 0,
            'balance_due' => $validated['balance_due'] ?? 0,
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'] ?? null,
            'payment_reference' => $validated['payment_reference'] ?? null,
            'payment_date' => $validated['payment_date'] ?? null,
            'credit_terms' => $validated['credit_terms'] ?? null,
            'credit_due_date' => $validated['credit_due_date'] ?? null,
            'delivery_method' => $validated['delivery_method'] ?? null,
            'delivery_date' => $validated['delivery_date'] ?? null,
            'delivery_status' => $validated['delivery_status'],
            'driver_name' => $validated['driver_name'] ?? null,
            'vehicle_plate_number' => $validated['vehicle_plate_number'] ?? null,
            'delivery_notes' => $validated['delivery_notes'] ?? null,
            'sales_rep_id' => $validated['sales_rep_id'],
            'approved_by' => $validated['approved_by'] ?? null,
            'commission_percent' => $validated['commission_percent'] ?? 0,
            'commission_amount' => $validated['commission_amount'] ?? 0,
            'internal_notes' => $validated['internal_notes'] ?? null,
            'customer_notes' => $validated['customer_notes'] ?? null,
            'terms_and_conditions' => $validated['terms_and_conditions'] ?? null,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => "Order '{$order->order_number}' updated successfully.",
                'order_id' => $order->id,
            ]);
        }

        return redirect()->back()->with('success', "Order '{$order->order_number}' updated successfully.");
    }

    public function destroy(Order $order)
    {
        $orderNumber = $order->order_number;
        
        // Delete associated order items
        $order->items()->delete();
        
        // Delete the order
        $order->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'message' => "Order '{$orderNumber}' deleted successfully.",
            ]);
        }

        return redirect()->back()->with('success', "Order '{$orderNumber}' deleted successfully.");
    }
}
