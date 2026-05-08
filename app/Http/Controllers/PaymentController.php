<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'company' => 'required|string|max:255',
            'payment_direction' => 'required|in:Incoming,Outgoing',
            'party_name' => 'required|string|max:255',
            'payment_method' => 'required|in:Cash,Bank Transfer,Mobile Money,Cheque,Direct Debit',
            'reference_number' => 'nullable|string|max:255',
            'payment_category' => 'required|in:Invoice Settlement,Expense Reimbursement,Loan Repayment,Advance Payment,Refund,Other',
            'linked_to' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|in:TZS,USD,EUR,KES',
            'exchange_rate' => 'required|numeric|min:0.01',
            'payment_status' => 'required|in:Completed,Pending,Failed,Reversed',
            'notes' => 'nullable|string',
            'proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'submit_mode' => 'required|in:draft,submit',
        ]);

        // Generate unique payment reference
        $reference = $this->generatePaymentReference();
        $validated['payment_reference'] = $reference;
        $validated['submit_mode'] = $request->input('submit_mode');

        // Handle file upload
        if ($request->hasFile('proof_of_payment')) {
            $file = $request->file('proof_of_payment');
            $originalName = $file->getClientOriginalName();
            $path = $file->store('payment-proofs', 'public');

            $validated['proof_of_payment_path'] = $path;
            $validated['original_proof_filename'] = $originalName;
        }

        $payment = Payment::create($validated);

        return redirect()->route('finance')
            ->with('success', "Payment {$reference} recorded successfully!");
    }

    /**
     * Show a specific payment.
     */
    public function show(Payment $payment)
    {
        return view('finance.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing a payment.
     */
    public function edit(Payment $payment)
    {
        return view('finance.payments.edit', compact('payment'));
    }

    /**
     * Update a payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'company' => 'required|string|max:255',
            'payment_direction' => 'required|in:Incoming,Outgoing',
            'party_name' => 'required|string|max:255',
            'payment_method' => 'required|in:Cash,Bank Transfer,Mobile Money,Cheque,Direct Debit',
            'reference_number' => 'nullable|string|max:255',
            'payment_category' => 'required|in:Invoice Settlement,Expense Reimbursement,Loan Repayment,Advance Payment,Refund,Other',
            'linked_to' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|in:TZS,USD,EUR,KES',
            'exchange_rate' => 'required|numeric|min:0.01',
            'payment_status' => 'required|in:Completed,Pending,Failed,Reversed',
            'notes' => 'nullable|string',
            'proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('proof_of_payment')) {
            // Delete old file if exists
            if ($payment->proof_of_payment_path) {
                Storage::disk('public')->delete($payment->proof_of_payment_path);
            }

            $file = $request->file('proof_of_payment');
            $originalName = $file->getClientOriginalName();
            $path = $file->store('payment-proofs', 'public');

            $validated['proof_of_payment_path'] = $path;
            $validated['original_proof_filename'] = $originalName;
        }

        $payment->update($validated);

        return redirect()->route('finance')
            ->with('success', "Payment {$payment->payment_reference} updated successfully!");
    }

    /**
     * Delete a payment.
     */
    public function destroy(Payment $payment)
    {
        $reference = $payment->payment_reference;

        // Delete proof file if exists
        if ($payment->proof_of_payment_path) {
            Storage::disk('public')->delete($payment->proof_of_payment_path);
        }

        
        DB::table('payments')->where('id', $payment->id)->delete();

        return redirect()->route('finance')
            ->with('success', "Payment {$reference} deleted successfully!");
    }

    /**
     * Download payment proof file.
     */
    public function downloadProof(Payment $payment)
    {
        if (!$payment->proof_of_payment_path) {
            return redirect()->back()->with('error', 'No proof file attached to this payment.');
        }

        $disk = Storage::disk('public');
        return response()->download(
            $disk->path($payment->proof_of_payment_path),
            $payment->original_proof_filename ?? 'payment-proof'
        );
    }

    /**
     * Generate a unique payment reference.
     */
    private function generatePaymentReference(): string
    {
        do {
            $random = random_int(10000, 99999);
            $reference = "PAY-{$random}";
        } while (Payment::where('payment_reference', $reference)->count() > 0);

        return $reference;
    }
}
