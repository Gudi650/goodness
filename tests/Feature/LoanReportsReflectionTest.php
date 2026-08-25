<?php

use App\Models\BankTransactions;
use App\Models\Company;
use App\Models\Loan;
use App\Models\LoanRepaymentSchedule;
use App\Models\User;
use App\Models\VirtualAccounts;
use App\Services\CashFlow\CashFlowReportService;
use App\Services\Finance\BalanceSheet\CurrentAssetsService;
use App\Services\Finance\BalanceSheet\CurrentLiabilitiesService;
use App\Services\Finance\BalanceSheet\NonCurrentLiabilitiesService;
use App\Services\NetIncome;
use App\Support\ReportFilters;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-08-14'));
    ReportFilters::reset();

    Schema::create('companies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('country')->nullable();
        $table->string('status')->default('Active');
        $table->timestamps();
    });
    Schema::create('virtual_accounts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('bank_name')->nullable();
        $table->decimal('balance', 15, 2)->default(0);
        $table->string('status')->default('active');
        $table->timestamps();
    });
    Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->string('loan_type')->default('external_borrow');
        $table->unsignedBigInteger('counterparty_company_id')->nullable();
        $table->unsignedBigInteger('employee_id')->nullable();
        $table->unsignedBigInteger('bank_id')->nullable();
        $table->unsignedBigInteger('source_bank_id')->nullable();
        $table->string('code')->unique();
        $table->string('lender');
        $table->decimal('principal', 15, 2);
        $table->decimal('interest_rate', 5, 2)->default(0);
        $table->string('interest_type')->default('Flat');
        $table->unsignedInteger('term_months')->default(12);
        $table->date('disbursement_date')->nullable();
        $table->boolean('is_disbursed')->default(false);
        $table->date('start_date');
        $table->date('maturity_date');
        $table->decimal('outstanding_balance', 15, 2)->default(0);
        $table->string('status')->default('Active');
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->nullable();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->timestamps();
    });
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('name')->nullable();
        $table->integer('stock')->default(0);
        $table->decimal('cost_per_unit', 15, 2)->default(0);
        $table->timestamps();
    });
    Schema::create('loan_repayment_schedules', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('loan_id');
        $table->unsignedInteger('installment_number');
        $table->date('due_date');
        $table->decimal('principal_portion', 15, 2)->default(0);
        $table->decimal('interest_portion', 15, 2)->default(0);
        $table->decimal('total_installment', 15, 2)->default(0);
        $table->string('status')->default('Pending');
        $table->timestamps();
    });
    Schema::create('create_assets', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('name')->nullable();
        $table->string('status')->nullable();
        $table->date('acquired')->nullable();
        $table->date('disposal_date')->nullable();
        $table->decimal('current_value', 15, 2)->default(0);
        $table->decimal('depreciation_value', 15, 2)->default(0);
        $table->decimal('disposal_proceeds', 15, 2)->default(0);
        $table->timestamps();
    });
    Schema::create('create_liabilities', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->unsignedBigInteger('category_id')->nullable();
        $table->string('name')->nullable();
        $table->string('term')->nullable();
        $table->date('due_date')->nullable();
        $table->decimal('current_amount', 15, 2)->default(0);
        $table->timestamps();
    });
    Schema::create('liability_categories', function (Blueprint $table) {
        $table->id();
        $table->string('category');
        $table->timestamps();
    });
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('status')->nullable();
        $table->decimal('tax_amount', 15, 2)->default(0);
        $table->decimal('total_amount', 15, 2)->default(0);
        $table->timestamps();
    });
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('status')->nullable();
        $table->decimal('amount', 15, 2)->default(0);
        $table->string('category')->nullable();
        $table->unsignedBigInteger('finance_item_id')->nullable();
        $table->timestamps();
    });
    Schema::create('dividends', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('status')->default('Declared');
        $table->decimal('amount', 15, 2)->default(0);
        $table->date('paid_at')->nullable();
        $table->date('declared_at')->nullable();
        $table->timestamps();
    });
    Schema::create('share_premuims', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->decimal('total_premium', 15, 2)->default(0);
        $table->timestamps();
    });
    Schema::create('asset_revaluations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->decimal('surplus', 15, 2)->default(0);
        $table->date('date_of_revaluation')->nullable();
        $table->timestamps();
    });
    Schema::create('bank_transactions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->unsignedBigInteger('bank_id')->nullable();
        $table->decimal('affecting_balance', 15, 2)->default(0);
        $table->string('transaction_type')->nullable();
        $table->timestamps();
    });
});

afterEach(function () {
    foreach ([
        'expenses', 'invoices', 'loan_repayment_schedules', 'loans', 'virtual_accounts', 'products',
        'create_assets', 'create_liabilities', 'liability_categories', 'companies', 'users',
        'dividends', 'share_premuims', 'asset_revaluations', 'bank_transactions',
    ] as $table) {
        Schema::dropIfExists($table);
    }
    ReportFilters::reset();
    Carbon::setTestNow();
});

test('balance sheet includes disbursed module loans as liabilities', function () {
    $company = Company::query()->create(['name' => 'Mining', 'country' => 'TZ', 'status' => 'Active']);
    ReportFilters::boot(request()->merge(['scope' => 'company', 'company_id' => $company->id]));

    Loan::query()->create([
        'company_id' => $company->id,
        'code' => 'LN-2026-1',
        'lender' => 'CRDB',
        'principal' => 1000,
        'is_disbursed' => true,
        'outstanding_balance' => 800,
        'start_date' => '2026-01-01',
        'maturity_date' => '2026-12-01', // within 12 months → current
        'disbursement_date' => '2026-01-15',
        'status' => 'Active',
    ]);

    Loan::query()->create([
        'company_id' => $company->id,
        'code' => 'LN-2026-2',
        'lender' => 'NMB',
        'principal' => 5000,
        'is_disbursed' => true,
        'outstanding_balance' => 4500,
        'start_date' => '2026-01-01',
        'maturity_date' => '2029-01-01', // long-term
        'disbursement_date' => '2026-01-15',
        'status' => 'Active',
    ]);

    // Not disbursed — must not appear
    Loan::query()->create([
        'company_id' => $company->id,
        'code' => 'LN-2026-3',
        'lender' => 'Hidden',
        'principal' => 999,
        'is_disbursed' => false,
        'outstanding_balance' => 999,
        'start_date' => '2026-01-01',
        'maturity_date' => '2026-12-01',
        'status' => 'Pending',
    ]);

    $current = app(CurrentLiabilitiesService::class)->getCurrentLiabilities()['short_term_loans'];
    $nonCurrent = app(NonCurrentLiabilitiesService::class)->getNonCurrentLiabilities()['long_term_loans'];

    expect((float) $current->sum('amount'))->toBe(800.0)
        ->and((float) $nonCurrent->sum('amount'))->toBe(4500.0);
});

test('balance sheet maps intercompany and employee loans by type without double counting', function () {
    $lender = Company::query()->create(['name' => 'Lender Co', 'country' => 'TZ', 'status' => 'Active']);
    $borrower = Company::query()->create(['name' => 'Borrower Co', 'country' => 'TZ', 'status' => 'Active']);
    $employee = User::query()->create(['name' => 'Worker', 'email' => 'w'.uniqid().'@ex.com', 'company_id' => $lender->id]);

    Loan::query()->create([
        'company_id' => $lender->id,
        'loan_type' => 'intercompany',
        'counterparty_company_id' => $borrower->id,
        'code' => 'LN-IC-1',
        'lender' => $lender->name,
        'principal' => 1000,
        'is_disbursed' => true,
        'outstanding_balance' => 1000,
        'start_date' => '2026-01-01',
        'maturity_date' => '2026-12-01',
        'status' => 'Active',
    ]);
    Loan::query()->create([
        'company_id' => $lender->id,
        'loan_type' => 'employee',
        'employee_id' => $employee->id,
        'code' => 'LN-EMP-1',
        'lender' => 'Employee: Worker',
        'principal' => 200,
        'is_disbursed' => true,
        'outstanding_balance' => 200,
        'start_date' => '2026-01-01',
        'maturity_date' => '2026-12-01',
        'status' => 'Active',
    ]);

    ReportFilters::reset();
    ReportFilters::boot(request()->merge(['scope' => 'company', 'company_id' => $lender->id]));
    $lenderAssets = app(CurrentAssetsService::class)->getCurrentAssets()['loan_receivables'];
    $lenderLiab = app(CurrentLiabilitiesService::class)->getCurrentLiabilities()['short_term_loans'];

    expect((float) $lenderAssets->sum('amount'))->toBe(1200.0)
        ->and((float) $lenderLiab->sum('amount'))->toBe(0.0);

    ReportFilters::reset();
    ReportFilters::boot(request()->merge(['scope' => 'company', 'company_id' => $borrower->id]));
    $borrowerAssets = app(CurrentAssetsService::class)->getCurrentAssets()['loan_receivables'];
    $borrowerLiab = app(CurrentLiabilitiesService::class)->getCurrentLiabilities()['short_term_loans'];

    expect((float) $borrowerAssets->sum('amount'))->toBe(0.0)
        ->and((float) $borrowerLiab->sum('amount'))->toBe(1000.0);

    ReportFilters::reset();
    ReportFilters::boot(request()->merge(['scope' => 'all']));
    $allAssets = app(CurrentAssetsService::class)->getCurrentAssets()['loan_receivables'];
    $allLiab = app(CurrentLiabilitiesService::class)->getCurrentLiabilities()['short_term_loans'];

    // Inter-company eliminated on consolidate; employee receivable remains.
    expect((float) $allAssets->sum('amount'))->toBe(200.0)
        ->and((float) $allLiab->sum('amount'))->toBe(0.0);
});

test('trial balance loan sections follow the same loan_type asset and liability rules', function () {
    $lender = Company::query()->create(['name' => 'TB Lender', 'country' => 'TZ', 'status' => 'Active']);
    $borrower = Company::query()->create(['name' => 'TB Borrower', 'country' => 'TZ', 'status' => 'Active']);

    Loan::query()->create([
        'company_id' => $lender->id,
        'loan_type' => 'intercompany',
        'counterparty_company_id' => $borrower->id,
        'code' => 'LN-TB-IC',
        'lender' => $lender->name,
        'principal' => 700,
        'is_disbursed' => true,
        'outstanding_balance' => 700,
        'start_date' => '2026-01-01',
        'maturity_date' => '2026-12-01',
        'status' => 'Active',
    ]);
    Loan::query()->create([
        'company_id' => $borrower->id,
        'loan_type' => 'external_borrow',
        'code' => 'LN-TB-EXT',
        'lender' => 'CRDB',
        'principal' => 300,
        'is_disbursed' => true,
        'outstanding_balance' => 300,
        'start_date' => '2026-01-01',
        'maturity_date' => '2026-12-01',
        'status' => 'Active',
    ]);

    // Same services TrialBalanceController::reportData() uses for BS lines.
    ReportFilters::reset();
    ReportFilters::boot(request()->merge(['scope' => 'company', 'company_id' => $lender->id]));
    $tbLenderDr = (float) app(CurrentAssetsService::class)->getCurrentAssets()['loan_receivables']->sum('amount');
    $tbLenderCr = (float) app(CurrentLiabilitiesService::class)->getCurrentLiabilities()['short_term_loans']->sum('amount');

    ReportFilters::reset();
    ReportFilters::boot(request()->merge(['scope' => 'company', 'company_id' => $borrower->id]));
    $tbBorrowerDr = (float) app(CurrentAssetsService::class)->getCurrentAssets()['loan_receivables']->sum('amount');
    $tbBorrowerCr = (float) app(CurrentLiabilitiesService::class)->getCurrentLiabilities()['short_term_loans']->sum('amount');

    expect($tbLenderDr)->toBe(700.0)
        ->and($tbLenderCr)->toBe(0.0)
        ->and($tbBorrowerDr)->toBe(0.0)
        ->and($tbBorrowerCr)->toBe(1000.0); // 700 IC liability + 300 external
});

test('cash flow disbursements count only confirmed is_disbursed loans', function () {
    $company = Company::query()->create(['name' => 'Mining', 'country' => 'TZ', 'status' => 'Active']);

    Loan::query()->create([
        'company_id' => $company->id,
        'code' => 'LN-A',
        'lender' => 'Bank',
        'principal' => 1000,
        'is_disbursed' => true,
        'outstanding_balance' => 1000,
        'disbursement_date' => '2026-03-01',
        'start_date' => '2026-03-01',
        'maturity_date' => '2027-03-01',
        'status' => 'Active',
    ]);
    Loan::query()->create([
        'company_id' => $company->id,
        'code' => 'LN-B',
        'lender' => 'Bank',
        'principal' => 2000,
        'is_disbursed' => false,
        'outstanding_balance' => 2000,
        'disbursement_date' => '2026-03-01',
        'start_date' => '2026-03-01',
        'maturity_date' => '2027-03-01',
        'status' => 'Pending',
    ]);

    $service = app(CashFlowReportService::class);
    $method = new ReflectionMethod($service, 'loanDisbursements');
    $method->setAccessible(true);

    expect((float) $method->invoke($service, $company->id, 2026))->toBe(1000.0);
});

test('net income subtracts paid loan interest', function () {
    $company = Company::query()->create(['name' => 'Mining', 'country' => 'TZ', 'status' => 'Active']);
    ReportFilters::boot(request()->merge(['scope' => 'company', 'company_id' => $company->id]));

    $loan = Loan::query()->create([
        'company_id' => $company->id,
        'code' => 'LN-I',
        'lender' => 'Bank',
        'principal' => 1000,
        'is_disbursed' => true,
        'outstanding_balance' => 500,
        'start_date' => '2026-01-01',
        'maturity_date' => '2027-01-01',
        'status' => 'Active',
    ]);

    LoanRepaymentSchedule::query()->create([
        'loan_id' => $loan->id,
        'installment_number' => 1,
        'due_date' => '2026-02-01',
        'principal_portion' => 400,
        'interest_portion' => 100,
        'total_installment' => 500,
        'status' => 'Paid',
        'created_at' => '2026-02-01 10:00:00',
        'updated_at' => '2026-02-01 10:00:00',
    ]);

    $ni = app(NetIncome::class)->calculateNetIncome($company->id, 2026);
    expect($ni)->toBe(-100.0);
});

test('cash flow report years follow the selected report filter range', function () {
    $company = Company::query()->create(['name' => 'Mining', 'country' => 'TZ', 'status' => 'Active']);
    ReportFilters::boot(request()->merge([
        'scope' => 'company',
        'company_id' => $company->id,
        'date_filter' => 'custom',
        'start_date' => '2025-12-15',
        'end_date' => '2026-03-31',
    ]));

    $data = app(CashFlowReportService::class)->build();

    expect($data['years'])->toHaveCount(2)
        ->and(array_map(fn ($year) => $year['date_label'], $data['years']))->toMatchArray([
            'December 31, 2025',
            'December 31, 2026',
        ]);
});

test('cash flow custom range with no matching transactions returns zero values', function () {
    $company = Company::query()->create(['name' => 'Mining', 'country' => 'TZ', 'status' => 'Active']);
    VirtualAccounts::query()->create([
        'company_id' => $company->id,
        'bank_name' => 'CBE',
        'balance' => 5000,
        'status' => 'active',
    ]);
    BankTransactions::query()->create([
        'company_id' => $company->id,
        'bank_id' => 1,
        'affecting_balance' => 500,
        'transaction_type' => 'income',
        'created_at' => '2025-11-01 10:00:00',
        'updated_at' => '2025-11-01 10:00:00',
    ]);

    ReportFilters::boot(request()->merge([
        'scope' => 'company',
        'company_id' => $company->id,
        'date_filter' => 'custom',
        'start_date' => '2026-12-24',
        'end_date' => '2026-12-29',
    ]));

    $data = app(CashFlowReportService::class)->build();

    expect($data['operatingChanges']['Cash invoices received'][0])->toBe(0.0)
        ->and($data['supplemental']['Bank transaction net movement'][0])->toBe(0.0)
        ->and($data['years'][0]['net_change'])->toBe(0.0);
});
