<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class ExpensesController extends Controller
{
    //

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

        //if there is no attachment found for the expense
        if (!$expense->attachment_path) {
            return redirect()->route('finance')->with('error', 'No attachment found for this expense.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download($expense->attachment_path, $expense->original_file_name ?: null);
    }

    /**
     * Approve an expense record.
     */

    public function approveExpense(Expense $expense)
    {
        

        //check it the usser is the manager of the company or an admin
        $user = Auth::user();

        $isAdmin = $user?->role?->name === 'Admin';
        $isManager = $user?->role?->name === 'Manager';
        $isCEO = $user?->role?->name === 'CEO';
        $isHr = $user?->role?->name === 'HR Manager';
        $isAccountant = $user?->role?->name === 'Accountant';
        



            if ( $isManager && $user->company_id === $expense->company_id) {
                $expense->status = 'checked';
                $expense->checked_by = Auth::id();
                $expense->save();

                return redirect()->route('finance')->with('success', 'Expense checked successfully. Awaiting issue from accountant .');
            }

            if ($isCEO) {

                if ($expense->status !== 'checked') {
                    return redirect()->route('finance')->with('error', 'Expense must be checked by the manager before it can be approved by the CEO.');
                }

                $expense->status = 'approved';
                $expense->approved_by = Auth::id();
                $expense->approved_at = Carbon::now();
                $expense->save();

                return redirect()->route('finance')->with('success', 'Expense approved successfully.');
            }

            if ($isAccountant) {

            if ($expense->status !== 'approved') {
                return redirect()->route('finance')->with('error', 'Expense must be approved by the CEO before it can be issued by the Accountant.');
            }

            $expense->status = 'issued';
            $expense->issued_by = Auth::id();
            $expense->save();

            return redirect()->route('finance')->with('success', 'Expense issued successfully.');
        }

        //if the user is neither of the above then will return an error message saying that the user is not authorized to approve the expense
        return redirect()->route('finance')->with('error', 'You are not authorized to approve this expense.');



    }


}

