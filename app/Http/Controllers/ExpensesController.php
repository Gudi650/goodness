<?php

namespace App\Http\Controllers;

use App\Models\BankTransactions;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\VirtualAccounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class ExpensesController extends Controller
{
    //

    /**
     * Show the expense review page for the creator.
     */
    public function reviewExpense(Expense $expense)
    {
        $user = Auth::user();

        abort_unless($user && ($user->id === $expense->created_by || $user->role?->name === 'Admin'), 403);

        $expense->load(['company', 'department', 'creator', 'checker', 'approver', 'issuer']);

        return view('finance.reviewexpenses', compact('expense'));
    }

    /**
     * Store the creator's feedback on how the expense money was used.
     */
    public function storeExpenseReview(Request $request, Expense $expense)
    {
        $user = Auth::user();

        abort_unless($user && ($user->id === $expense->created_by || $user->role?->name === 'Admin'), 403);

        $validated = $request->validate([
            'review_feedback' => 'nullable|string|max:5000',
            'review_items' => 'nullable|array',
            'review_items.*.description' => 'required_with:review_items|string|max:255',
            'review_items.*.amount' => 'nullable|numeric|min:0',
            'review_items.*.note' => 'nullable|string|max:500',
            'review_evidence' => 'nullable|array',
            'review_evidence.*' => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $reviewItems = collect($validated['review_items'] ?? [])
            ->filter(fn ($item) => filled($item['description'] ?? null) || filled($item['amount'] ?? null) || filled($item['note'] ?? null))
            ->values()
            ->map(function (array $item) {
                return [
                    'description' => $item['description'] ?? '',
                    'amount' => isset($item['amount']) && $item['amount'] !== '' ? (float) $item['amount'] : null,
                    'note' => $item['note'] ?? null,
                ];
            })
            ->all();

        $reviewEvidence = [];
        if ($request->hasFile('review_evidence')) {
            foreach ($request->file('review_evidence') as $evidenceFile) {
                if (!$evidenceFile) {
                    continue;
                }

                $reviewEvidence[] = [
                    'path' => $evidenceFile->store('expense-review-evidence', 'public'),
                    'name' => $evidenceFile->getClientOriginalName(),
                ];
            }
        }

        $expense->forceFill([

            'review_feedback' => $validated['review_feedback'],
            'review_items' => $reviewItems,
            'review_evidence_paths' => $reviewEvidence,
            'reviewed_at' => now(),
        ])->save();

        return redirect()
            ->route('finance')
            ->with('success', 'Your expense review has been submitted successfully.');
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
            'payment_method' => 'nullable|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'vat_included' => 'required|boolean',
            'vat_rate' => 'nullable|numeric|min:0',
            'vat_amount' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'submit_mode' => 'nullable|in:draft,submit',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'term' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'sub_category_id' => 'nullable|exists:finance_items,id',
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

        /*check if the bank submitted is of same company and also check if the bank has sufficient money as well
        if (isset($validated['bank_id'])) {
            $bankId = $validated['bank_id'];
            $companyId = $validated['company_id'];
            $amount = $validated['amount'];

            if (!$this->validateBankForExpense($bankId, $companyId, $amount)) {
                return redirect()->back()->with('error', 'Invalid bank account or insufficient funds for this expense.');
            }

        } */

        //force a temporary payment method
        $validated['payment_method'] = 'To be determined';

        $expense = new Expense();
        $expense->forceFill([
            'expense_number' => $validated['expense_number'],
            'expense_date' => $validated['expense_date'],
            'company_id' => (int) $validated['company_id'],
            'department_id' => (int) $validated['department_id'],
            'created_by' => Auth::id(),
            'status' => $status,
            'category' => $validated['category'],
            'sub_category_id' => $validated['sub_category_id'] ?? null,
            'description' => $validated['description'] ?? null,
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
            'term' => $validated['term'] ?? null,
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

        //$isAdmin = $user?->role?->name === 'Admin';
        $isManager = $user?->role?->name === 'Manager';
        $isCEO = $user?->role?->name === 'CEO';
        //$isHr = $user?->role?->name === 'HR Manager';
        $isAccountant = $user?->role?->name === 'Accountant';
        



            if ( $isManager && $user->company_id === $expense->company_id) {
                $expense->status = 'checked';
                $expense->checked_by = Auth::id();
                $expense->save();

                return redirect()->route('finance')->with('success', 'Expense checked successfully. Awaiting checking from CEO .');
            }

            if ($isCEO) {

                if ($expense->status !== 'checked') {
                    return redirect()->route('finance')->with('error', 'Expense must be checked by the manager before it can be approved by the CEO.');
                }

                $expense->status = 'approved';
                $expense->approved_by = Auth::id();
                $expense->save();

                return redirect()->route('finance')->with('success', 'Expense approved successfully.');
            }

            //here is the issueing part done by accountant, 
            //the accountant will issue the expense and also deduct the amount from the bank account balance and also record the transaction in the transactions table for record keeping and future reference, this will be helpful for generating financial reports and also for auditing purposes
            if ($isAccountant) {
                if ($expense->status !== 'approved') {
                    return redirect()->route('finance')->with('error', 'Expense must be approved by the CEO before it can be issued by the Accountant.');
                }

                try {
                    DB::transaction(function () use ($expense) {
                    
                        // Deduct from bank account
                        $this->deductAmountFromBankAccount($expense->bank_id, $expense->amount);

                        // Record transaction
                        $this->recordBankTransaction($expense->bank_id, $expense->company_id, $expense->amount, $expense->id);

                        // Mark expense as issued
                        $expense->status = 'issued';
                        $expense->issued_by = Auth::id();
                        $expense->save();
                        
                    });

                    return redirect()->route('finance')->with('success', 'Expense issued successfully, balance deducted and transaction recorded.');
                } catch (\Exception $e) {
                    \Log::error('Failed to issue expense: '.$e->getMessage(), [
                        'expense_id' => $expense->id,
                        'bank_id'    => $expense->bank_id,
                        'amount'     => $expense->amount,
                    ]);

                    return redirect()->route('finance')->with('error', 'Failed to issue expense. Please try again.');
                }
            }

        //if the user is neither of the above then will return an error message saying that the user is not authorized to approve the expense
        return redirect()->route('finance')->with('error', 'You are not authorized to approve this expense.');

    }


    //check if the bank submitted is of same company and also check if the bank has sufficient money as well
    protected function validateBankForExpense($bankId, $companyId, $amount)
    {
        $bankAccount = VirtualAccounts::where('id', $bankId)
            ->where('company_id', $companyId)
            ->first();

        if (!$bankAccount) {
            return false; // Bank account does not belong to the company
        }

        /*
        if ($bankAccount->balance < $amount) {
            return false; // Insufficient funds in the bank account
        }
            */

        return true; // Bank account is valid and has sufficient funds
    }

    //if it passes the validation then we will deduct the amount from the bank account balance
    protected function deductAmountFromBankAccount($bankId, $amount)
    {

        $bankAccount = VirtualAccounts::find($bankId);

        if ($bankAccount) {
            $bankAccount->balance -= $amount;
            $bankAccount->save();
        }

    }

    //now store the transcation in the transactions table as well for record keeping and future reference, this will be helpful for generating financial reports and also for auditing purposes
    protected function recordBankTransaction($bankId, $companyId, $amount, $expenseId)
    {
        BankTransactions::create([
            'bank_id' => $bankId,
            'company_id' => $companyId,
            'balance_after' => VirtualAccounts::find($bankId)->balance,
            'affecting_balance' => -$amount,
            'expense_id' => $expenseId,
            'transaction_type' => 'expense',
        ]);
    }

    //function to get the inout field of the bank from the accountnt during issueing og the expense
    public function issueExpense(Expense $expense, Request $request)
    {


        //get the requested data and validate it 
        $validated = $request->validate([
            'bank_id' => 'required|exists:virtual_accounts,id',
            'payment_method' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
        ]);

        //dd($validated);


        //check if the bank submitted is of same company and also check if the bank has sufficient money as well
        if (!$this->validateBankForExpense($validated['bank_id'], $expense->company_id, $expense->amount)) {
            return redirect()->back()->with('error', 'Invalid bank account or insufficient funds for this expense.');
        }

        //check it the usser is the manager of the company or an admin
        $user = Auth::user();

        $isAccountant = $user?->role?->name === 'Accountant';

        //dd($isAccountant);

        if ($isAccountant) {

            if ($expense->status !== 'approved') {
                return redirect()->route('finance')->with('error', 'Expense must be approved by the CEO before it can be issued by the Accountant.');
            }

            try {
                DB::transaction(function () use ($expense, $validated) {


                    // Mark expense as issued
                    $expense->status = 'issued';
                    $expense->issued_by = Auth::id();
                    $expense->payment_method = $validated['payment_method'];
                    $expense->reference_number = $validated['reference'];
                    $expense->issued_at = Carbon::now();
                    $expense->bank_id = $validated['bank_id'];

                    // Deduct from bank account
                    $this->deductAmountFromBankAccount($validated['bank_id'], $expense->amount);

                    // Record transaction
                    $this->recordBankTransaction($validated['bank_id'], $expense->company_id, $expense->amount, $expense->id);

                    $expense->save();
                    
                });

                return redirect()->route('finance')->with('success', 'Expense issued successfully, balance deducted and transaction recorded.');
            } catch (\Exception $e) {
                \Log::error('Failed to issue expense: '.$e->getMessage(), [
                    'expense_id' => $expense->id,
                    'bank_id'    => $validated['bank_id'],
                    'amount'     => $expense->amount,
                ]);

                return redirect()->route('finance')->with('error', 'Failed to issue expense. Please try again.');
            }
        }

    }
        



}

