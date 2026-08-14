<?php

use App\Models\Company;
use App\Models\Loan;
use App\Models\LoanRepaymentSchedule;
use App\Models\Role;
use App\Models\User;
use App\Models\VirtualAccounts;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('roles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->timestamps();
    });
    Schema::create('companies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('country')->nullable();
        $table->string('status')->default('Active');
        $table->timestamps();
    });
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->unsignedBigInteger('role_id')->nullable();
        $table->unsignedBigInteger('company_id')->nullable();
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
        $table->unsignedBigInteger('bank_id')->nullable();
        $table->string('code')->unique();
        $table->string('lender');
        $table->decimal('principal', 15, 2);
        $table->decimal('interest_rate', 5, 2)->default(0);
        $table->string('interest_type')->default('Flat');
        $table->unsignedInteger('term_months')->default(1);
        $table->date('disbursement_date')->nullable();
        $table->boolean('is_disbursed')->default(false);
        $table->date('start_date');
        $table->date('maturity_date');
        $table->decimal('outstanding_balance', 15, 2)->default(0);
        $table->decimal('total_interest_payable', 15, 2)->default(0);
        $table->decimal('total_repayable', 15, 2)->default(0);
        $table->string('status')->default('Active');
        $table->unsignedBigInteger('approved_by_id')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('loan_repayment_schedules', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('loan_id');
        $table->unsignedInteger('installment_number');
        $table->date('due_date');
        $table->decimal('principal_portion', 15, 2);
        $table->decimal('interest_portion', 15, 2);
        $table->decimal('total_installment', 15, 2);
        $table->string('status')->default('Pending');
        $table->timestamps();
    });
});

afterEach(function () {
    foreach (['loan_repayment_schedules', 'loans', 'virtual_accounts', 'users', 'companies', 'roles'] as $t) {
        Schema::dropIfExists($t);
    }
});

function seedPayContext(): array
{
    $company = Company::query()->create(['name' => 'Mining', 'country' => 'TZ', 'status' => 'Active']);
    $role = Role::query()->create(['name' => 'Admin']);
    $user = User::query()->create([
        'name' => 'Admin',
        'email' => 'a'.uniqid().'@ex.com',
        'password' => Hash::make('x'),
        'role_id' => $role->id,
        'company_id' => $company->id,
    ]);
    $bank = VirtualAccounts::query()->create([
        'company_id' => $company->id,
        'bank_name' => 'CRDB',
        'balance' => 5000,
        'status' => 'active',
    ]);
    $loan = Loan::query()->create([
        'company_id' => $company->id,
        'bank_id' => $bank->id,
        'code' => 'LN-2026-200',
        'lender' => 'Bank',
        'principal' => 1000,
        'start_date' => '2026-08-01',
        'maturity_date' => '2027-08-01',
        'outstanding_balance' => 1000,
        'is_disbursed' => true,
        'status' => 'Active',
    ]);
    $schedule = LoanRepaymentSchedule::query()->create([
        'loan_id' => $loan->id,
        'installment_number' => 1,
        'due_date' => '2026-09-01',
        'principal_portion' => 800,
        'interest_portion' => 200,
        'total_installment' => 1000,
        'status' => 'Pending',
    ]);

    return compact('user', 'bank', 'loan', 'schedule');
}

test('mark paid deducts installment from loan bank and reduces outstanding', function () {
    ['user' => $user, 'bank' => $bank, 'loan' => $loan, 'schedule' => $schedule] = seedPayContext();

    $this->actingAs($user)->patch('/loans/schedule/'.$schedule->id.'/mark-paid')->assertRedirect();

    $bank->refresh();
    $loan->refresh();
    $schedule->refresh();

    expect($schedule->status)->toBe('Paid')
        ->and((float) $bank->balance)->toBe(4000.0)
        ->and((float) $loan->outstanding_balance)->toBe(200.0);
});

test('mark paid does not double-charge when already paid', function () {
    ['user' => $user, 'bank' => $bank, 'schedule' => $schedule] = seedPayContext();
    $schedule->update(['status' => 'Paid']);

    $this->actingAs($user)->patch('/loans/schedule/'.$schedule->id.'/mark-paid');

    $bank->refresh();
    expect((float) $bank->balance)->toBe(5000.0);
});

test('mark paid blocks when bank balance is insufficient', function () {
    ['user' => $user, 'bank' => $bank, 'schedule' => $schedule] = seedPayContext();
    $bank->update(['balance' => 50]);

    $this->actingAs($user)->patch('/loans/schedule/'.$schedule->id.'/mark-paid');

    $schedule->refresh();
    $bank->refresh();
    expect($schedule->status)->toBe('Pending')
        ->and((float) $bank->balance)->toBe(50.0);
});
