<?php

namespace App\Http\Controllers\Loans;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Loan;
use App\Models\User;
use App\Models\VirtualAccounts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * Renders the Loans page. This loads the loan register,
     * companies, approvers, and virtual accounts for dropdowns.
     */
    public function index(): View
    {
        $loans = Loan::with(['company', 'bankAccount', 'approvedBy', 'repaymentSchedule' => function ($query) {
                $query->orderBy('installment_number');
            }])
            ->orderByDesc('start_date')
            ->get();

        $companies = Company::orderBy('name')->get(['id', 'name']);
        $approvers = User::orderBy('name')->get(['id', 'name']);
        $virtualAccounts = VirtualAccounts::where('status', 'active')
            ->orderBy('bank_name')
            ->get(['id', 'bank_name', 'account_name', 'account_number']);

        return view('loan', compact('loans', 'companies', 'approvers', 'virtualAccounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'bank_id' => ['nullable', 'exists:virtual_accounts,id'],
            'lender' => ['required', 'string', 'max:150'],
            'principal' => ['required', 'numeric', 'min:0'],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'interest_type' => ['required', 'in:Flat,Reducing Balance'],
            'term_months' => ['required', 'integer', 'min:1'],
            'disbursement_date' => ['nullable', 'date'],
            'start_date' => ['required', 'date'],
            'maturity_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:Active,Closed,Overdue,Defaulted'],
            'purpose' => ['nullable', 'string'],
            'collateral' => ['nullable', 'string'],
            'guarantor' => ['nullable', 'string', 'max:150'],
            'approved_by_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use (&$loan, $data) {
            $attempts = 0;
            do {
                $data['code'] = Loan::generateNextCode();
                try {
                    $loan = Loan::create($data);
                    break;
                } catch (\Illuminate\Database\QueryException $e) {
                    $attempts++;
                    if ($attempts >= 3) {
                        throw $e;
                    }
                }
            } while (true);

            // Auto-build the amortization schedule from the terms just entered.
            $loan->generateSchedule();

            // Money flow logic: Add loan principal to selected bank account balance
            if (!empty($data['bank_id'])) {
                $bankAccount = VirtualAccounts::find($data['bank_id']);
                if ($bankAccount) {
                    $bankAccount->increment('balance', $data['principal']);
                }
            }
        });

        return back()->with('success', "Loan {$loan->code} added, schedule generated, and account balance updated.");
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'bank_id' => ['nullable', 'exists:virtual_accounts,id'],
            'lender' => ['required', 'string', 'max:150'],
            'principal' => ['required', 'numeric', 'min:0'],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'interest_type' => ['required', 'in:Flat,Reducing Balance'],
            'term_months' => ['required', 'integer', 'min:1'],
            'disbursement_date' => ['nullable', 'date'],
            'start_date' => ['required', 'date'],
            'maturity_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:Active,Closed,Overdue,Defaulted'],
            'purpose' => ['nullable', 'string'],
            'collateral' => ['nullable', 'string'],
            'guarantor' => ['nullable', 'string', 'max:150'],
            'approved_by_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $termsChanged = $loan->principal != $data['principal']
            || $loan->interest_rate != $data['interest_rate']
            || $loan->interest_type !== $data['interest_type']
            || $loan->term_months != $data['term_months']
            || $loan->start_date->toDateString() !== $data['start_date'];

        $loan->update($data);

        // Rebuild schedule if parameters driving amortisation changed
        if ($termsChanged) {
            $loan->generateSchedule();
        }

        return back()->with('success', "Loan {$loan->code} updated.");
    }

    public function destroy(Loan $loan): RedirectResponse
    {
        $loan->delete();

        return back()->with('success', "Loan {$loan->code} removed.");
    }
}