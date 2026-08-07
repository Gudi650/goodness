<?php

namespace App\Http\Controllers\Loans;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * Renders the Loans page. This currently loads both the loan register
     * and the repayment schedule since the page hasn't been split into
     * subpages yet — once you do that split, move the schedule-related
     * data into LoanRepaymentScheduleController and give the view its own
     * route.
     */
    public function index(): View
    {
        $loans = Loan::with(['company', 'approvedBy', 'repaymentSchedule' => function ($query) {
                $query->orderBy('installment_number');
            }])
            ->orderByDesc('start_date')
            ->get();

        $companies = Company::orderBy('name')->get(['id', 'name']);
        $approvers = User::orderBy('name')->get(['id', 'name']);

        // The Repayment Schedule tab groups by loan (one row per loan, with
        // an expandable installment breakdown), so it reads straight off
        // $loans -> repaymentSchedule instead of a separate flat query.
        return view('loan', compact('loans', 'companies', 'approvers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
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

        // The code is never taken from the form — it's generated here so
        // it can't be duplicated, mistyped, or left in the wrong format.
        // The retry loop only matters if two loans are saved in the same
        // instant and both compute the same "next" number before either
        // has committed; on the rare collision it just tries the next one.
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

        return back()->with('success', "Loan {$loan->code} added and schedule generated.");
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
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

        // Code is intentionally not editable — it's the permanent
        // reference the schedule and (eventually) repayments are keyed to.

        $termsChanged = $loan->principal != $data['principal']
            || $loan->interest_rate != $data['interest_rate']
            || $loan->interest_type !== $data['interest_type']
            || $loan->term_months != $data['term_months']
            || $loan->start_date->toDateString() !== $data['start_date'];

        $loan->update($data);

        // If the numbers driving the schedule changed, rebuild it. If only
        // status/notes/etc changed, leave the existing schedule untouched.
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