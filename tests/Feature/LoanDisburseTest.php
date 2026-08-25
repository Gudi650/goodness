<?php

use App\Models\Company;
use App\Models\Loan;
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
        $table->text('description')->nullable();
        $table->timestamps();
    });

    Schema::create('companies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('country')->nullable();
        $table->decimal('revenue', 15, 2)->default(0);
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
        $table->string('account_name')->nullable();
        $table->string('account_number')->nullable();
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
        $table->decimal('interest_rate', 5, 2);
        $table->string('interest_type');
        $table->unsignedInteger('term_months');
        $table->date('disbursement_date')->nullable();
        $table->boolean('is_disbursed')->default(false);
        $table->date('start_date');
        $table->date('maturity_date');
        $table->decimal('outstanding_balance', 15, 2)->default(0);
        $table->decimal('total_interest_payable', 15, 2)->default(0);
        $table->decimal('total_repayable', 15, 2)->default(0);
        $table->string('status')->default('Pending');
        $table->text('purpose')->nullable();
        $table->text('collateral')->nullable();
        $table->string('guarantor')->nullable();
        $table->unsignedBigInteger('approved_by_id')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
});

afterEach(function () {
    Schema::dropIfExists('loans');
    Schema::dropIfExists('virtual_accounts');
    Schema::dropIfExists('users');
    Schema::dropIfExists('companies');
    Schema::dropIfExists('roles');
});

test('confirm disbursement credits bank and sets is_disbursed without changing expected date', function () {
    $company = Company::query()->create(['name' => 'Mining', 'country' => 'TZ', 'status' => 'Active']);
    $role = Role::query()->create(['name' => 'Admin']);
    $user = User::query()->create([
        'name' => 'Admin',
        'email' => 'admin'.uniqid().'@example.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
        'company_id' => $company->id,
    ]);

    $bank = VirtualAccounts::query()->create([
        'company_id' => $company->id,
        'bank_name' => 'CRDB',
        'account_name' => 'Ops',
        'account_number' => '111',
        'balance' => 1000,
        'status' => 'active',
    ]);

    $expectedDate = '2026-08-20';
    $loan = Loan::query()->create([
        'company_id' => $company->id,
        'bank_id' => $bank->id,
        'code' => 'LN-2026-100',
        'lender' => 'Bank',
        'principal' => 500,
        'interest_rate' => 10,
        'interest_type' => 'Flat',
        'term_months' => 12,
        'disbursement_date' => $expectedDate,
        'is_disbursed' => false,
        'start_date' => '2026-08-01',
        'maturity_date' => '2027-08-01',
        'status' => 'Pending',
        'approved_by_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->post('/loans/'.$loan->id.'/disburse');

    $response->assertRedirect();
    $loan->refresh();
    $bank->refresh();

    expect($loan->is_disbursed)->toBeTrue()
        ->and($loan->status)->toBe('Active')
        ->and($loan->disbursement_date->toDateString())->toBe($expectedDate)
        ->and((float) $bank->balance)->toBe(1500.0);
});

test('confirm disbursement is blocked when already disbursed', function () {
    $company = Company::query()->create(['name' => 'Mining', 'country' => 'TZ', 'status' => 'Active']);
    $role = Role::query()->create(['name' => 'Admin']);
    $user = User::query()->create([
        'name' => 'Admin',
        'email' => 'admin'.uniqid().'@example.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
        'company_id' => $company->id,
    ]);

    $bank = VirtualAccounts::query()->create([
        'company_id' => $company->id,
        'bank_name' => 'CRDB',
        'account_name' => 'Ops',
        'account_number' => '111',
        'balance' => 1000,
        'status' => 'active',
    ]);

    $loan = Loan::query()->create([
        'company_id' => $company->id,
        'bank_id' => $bank->id,
        'code' => 'LN-2026-101',
        'lender' => 'Bank',
        'principal' => 500,
        'interest_rate' => 10,
        'interest_type' => 'Flat',
        'term_months' => 12,
        'disbursement_date' => '2026-08-20',
        'is_disbursed' => true,
        'start_date' => '2026-08-01',
        'maturity_date' => '2027-08-01',
        'status' => 'Active',
        'approved_by_id' => $user->id,
    ]);

    $this->actingAs($user)->post('/loans/'.$loan->id.'/disburse');

    $bank->refresh();
    expect((float) $bank->balance)->toBe(1000.0);
});
