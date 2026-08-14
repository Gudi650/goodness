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
     * Renders the Loans page.
     */
    public function index(): View
    {
        $activeCompanyId = session('active_company_id');

        $loans = Loan::with(['company', 'bankAccount', 'approvedBy', 'repaymentSchedule' => function ($query) {
                $query->orderBy('installment_number');
            }])
            ->when(! empty($activeCompanyId), fn ($query) => $query->where('company_id', $activeCompanyId))
            ->orderByDesc('start_date')
            ->get();

        $companies = Company::orderBy('name')->get(['id', 'name']);

        $approvers = User::orderBy('name')->get(['id', 'name']);

        $virtualAccounts = VirtualAccounts::where('status', 'active')
            ->when(! empty($activeCompanyId), fn ($query) => $query->where('company_id', $activeCompanyId))
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
            'status' => ['required', 'in:Pending,Active,Closed,Overdue,Defaulted'],
            'purpose' => ['nullable', 'string'],
            'collateral' => ['nullable', 'string'],
            'guarantor' => ['nullable', 'string', 'max:150'],
            'approved_by_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $loan = DB::transaction(function () use ($data) {
                $attempts = 0;
                do {
                    $data['code'] = Loan::generateNextCode();
                    try {
                        $loan = Loan::create($data);
                        break;
                    } catch (\Illuminate\Database\QueryException $e) {
                        $attempts++;
                        $isDuplicateCode = str_contains(strtolower($e->getMessage()), 'duplicate')
                            || (string) $e->getCode() === '23000';

                        if (! $isDuplicateCode || $attempts >= 5) {
                            throw $e;
                        }
                    }
                } while (true);

                $loan->generateSchedule();

                return $loan;
            });
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Could not save loan: '.$e->getMessage());
        }

        return back()->with('success', "Loan {$loan->code} recorded and repayment schedule generated.");
    }

    /**
     * Confirms loan disbursement: adds principal to bank balance and marks active.
     */
    public function disburse(Request $request, Loan $loan): RedirectResponse
    {
        // 1. Verify that loan is approved first
        if (empty($loan->approved_by_id)) {
            return back()->with('error', "Loan {$loan->code} must be approved by an authorized user before disbursement.");
        }

        // 2. Prevent duplicate disbursal
        if ($loan->disbursement_date !== null && $loan->status === 'Active') {
            return back()->with('error', "Loan {$loan->code} has already been disbursed on {$loan->disbursement_date->format('d M Y')}.");
        }

        // 3. Ensure a bank account is selected to receive funds
        if (empty($loan->bank_id)) {
            return back()->with('error', "Please assign a target Bank/Virtual Account to loan {$loan->code} before disbursing.");
        }

        DB::transaction(function () use ($loan) {
            // Update bank balance on day of disbursement
            $bankAccount = VirtualAccounts::findOrFail($loan->bank_id);
            $bankAccount->increment('balance', $loan->principal);

            // Update loan status and record actual disbursement date
            $loan->update([
                'status' => 'Active',
                'disbursement_date' => now(),
            ]);
        });

        return back()->with('success', "Loan {$loan->code} successfully disbursed. Funds (TZS " . number_format($loan->principal) . ") deposited to bank account.");
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
            'status' => ['required', 'in:Pending,Active,Closed,Overdue,Defaulted'],
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