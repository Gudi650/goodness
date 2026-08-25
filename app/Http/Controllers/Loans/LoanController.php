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
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(): View
    {
        $activeCompanyId = session('active_company_id');

        $loans = Loan::with([
            'company',
            'counterpartyCompany',
            'employee',
            'bankAccount',
            'sourceBankAccount',
            'approvedBy',
            'repaymentSchedule' => fn ($query) => $query->orderBy('installment_number'),
        ])
            ->when(! empty($activeCompanyId), function ($query) use ($activeCompanyId) {
                $query->where(function ($inner) use ($activeCompanyId) {
                    $inner->where('company_id', $activeCompanyId)
                        ->orWhere('counterparty_company_id', $activeCompanyId);
                });
            })
            ->orderByDesc('start_date')
            ->get();

        $companies = Company::orderBy('name')->get(['id', 'name']);

        $approvers = User::orderBy('name')->get(['id', 'name']);

        // All active banks (modal filters by company for from/to).
        $virtualAccounts = VirtualAccounts::where('status', 'active')
            ->orderBy('bank_name')
            ->get(['id', 'company_id', 'bank_name', 'account_name', 'account_number']);

        $employees = User::with('company:id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'company_id']);

        return view('loan', compact('loans', 'companies', 'approvers', 'virtualAccounts', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedLoanData($request);

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

    public function disburse(Request $request, Loan $loan): RedirectResponse
    {
        if (empty($loan->approved_by_id)) {
            return back()->with('error', "Loan {$loan->code} must be approved before disbursement can be confirmed.");
        }

        if ($loan->is_disbursed) {
            return back()->with('error', "Loan {$loan->code} has already been confirmed as disbursed.");
        }

        $type = $loan->loan_type ?: Loan::TYPE_EXTERNAL_BORROW;

        if ($type === Loan::TYPE_EXTERNAL_BORROW && empty($loan->bank_id)) {
            return back()->with('error', "Please assign a receiving bank to loan {$loan->code} before disbursing.");
        }

        if ($type === Loan::TYPE_INTERCOMPANY && (empty($loan->bank_id) || empty($loan->source_bank_id))) {
            return back()->with('error', "Inter-company loan {$loan->code} needs both source and receiving banks before disbursing.");
        }

        if ($type === Loan::TYPE_EMPLOYEE && empty($loan->source_bank_id)) {
            return back()->with('error', "Employee loan {$loan->code} needs a source bank before disbursing.");
        }

        try {
            DB::transaction(function () use ($loan, $type) {
                if ($type === Loan::TYPE_EXTERNAL_BORROW) {
                    VirtualAccounts::findOrFail($loan->bank_id)->increment('balance', $loan->principal);
                } elseif ($type === Loan::TYPE_INTERCOMPANY) {
                    $source = VirtualAccounts::findOrFail($loan->source_bank_id);
                    $destination = VirtualAccounts::findOrFail($loan->bank_id);

                    if ((float) $source->balance < (float) $loan->principal) {
                        throw ValidationException::withMessages([
                            'source_bank_id' => 'Source bank has insufficient balance for this disbursement.',
                        ]);
                    }

                    $source->decrement('balance', $loan->principal);
                    $destination->increment('balance', $loan->principal);
                } else {
                    $source = VirtualAccounts::findOrFail($loan->source_bank_id);

                    if ((float) $source->balance < (float) $loan->principal) {
                        throw ValidationException::withMessages([
                            'source_bank_id' => 'Source bank has insufficient balance for this disbursement.',
                        ]);
                    }

                    $source->decrement('balance', $loan->principal);
                }

                $loan->update([
                    'is_disbursed' => true,
                    'status' => 'Active',
                ]);
            });
        } catch (ValidationException $e) {
            return back()->with('error', $e->validator->errors()->first());
        }

        return back()->with('success', "Loan {$loan->code} disbursement confirmed. Principal TZS ".number_format((float) $loan->principal).' moved per loan type.');
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $data = $this->validatedLoanData($request);

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

    /**
     * @return array<string, mixed>
     */
    protected function validatedLoanData(Request $request): array
    {
        $data = $request->validate([
            'loan_type' => ['required', Rule::in([Loan::TYPE_EXTERNAL_BORROW, Loan::TYPE_INTERCOMPANY, Loan::TYPE_EMPLOYEE])],
            'company_id' => ['required', 'exists:companies,id'],
            'counterparty_company_id' => ['nullable', 'exists:companies,id', 'different:company_id'],
            'employee_id' => ['nullable', 'exists:users,id'],
            'bank_id' => ['nullable', 'exists:virtual_accounts,id'],
            'source_bank_id' => ['nullable', 'exists:virtual_accounts,id', 'different:bank_id'],
            'lender' => ['nullable', 'string', 'max:150'],
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

        $type = $data['loan_type'];

        if ($type === Loan::TYPE_EXTERNAL_BORROW) {
            if (empty($data['lender'])) {
                throw ValidationException::withMessages(['lender' => 'Lender is required for external loans.']);
            }
            $data['counterparty_company_id'] = null;
            $data['employee_id'] = null;
            $data['source_bank_id'] = null;
        }

        if ($type === Loan::TYPE_INTERCOMPANY) {
            if (empty($data['counterparty_company_id'])) {
                throw ValidationException::withMessages(['counterparty_company_id' => 'Borrowing company is required.']);
            }
            if (empty($data['source_bank_id']) || empty($data['bank_id'])) {
                throw ValidationException::withMessages([
                    'source_bank_id' => 'Both source (from) and receiving (to) banks are required for inter-company loans.',
                ]);
            }

            $source = VirtualAccounts::find($data['source_bank_id']);
            $destination = VirtualAccounts::find($data['bank_id']);

            if ((int) $source?->company_id !== (int) $data['company_id']) {
                throw ValidationException::withMessages(['source_bank_id' => 'Source bank must belong to the lending company.']);
            }
            if ((int) $destination?->company_id !== (int) $data['counterparty_company_id']) {
                throw ValidationException::withMessages(['bank_id' => 'Receiving bank must belong to the borrowing company.']);
            }

            $lenderCompany = Company::find($data['company_id']);
            $data['lender'] = $lenderCompany?->name ?? 'Inter-company';
            $data['employee_id'] = null;
        }

        if ($type === Loan::TYPE_EMPLOYEE) {
            if (empty($data['employee_id'])) {
                throw ValidationException::withMessages(['employee_id' => 'Employee is required.']);
            }
            if (empty($data['source_bank_id'])) {
                throw ValidationException::withMessages(['source_bank_id' => 'Source bank is required for employee loans.']);
            }

            $employee = User::find($data['employee_id']);
            if ((int) $employee?->company_id !== (int) $data['company_id']) {
                throw ValidationException::withMessages(['employee_id' => 'Employee must belong to the selected company.']);
            }

            $source = VirtualAccounts::find($data['source_bank_id']);
            if ((int) $source?->company_id !== (int) $data['company_id']) {
                throw ValidationException::withMessages(['source_bank_id' => 'Source bank must belong to the selected company.']);
            }

            $data['lender'] = $employee?->name ? 'Employee: '.$employee->name : 'Employee loan';
            $data['counterparty_company_id'] = null;
            $data['bank_id'] = null;
        }

        return $data;
    }
}
