<?php

use App\Models\Company;
use App\Models\Department;
use App\Models\Expense;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-08-10'));

    Schema::create('roles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->text('description')->nullable();
        $table->timestamps();
    });

    Schema::create('companies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('country');
        $table->decimal('revenue', 15, 2)->default(0);
        $table->string('status')->default('Active');
        $table->timestamps();
    });

    Schema::create('departments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->string('name');
        $table->text('description')->nullable();
        $table->timestamps();
    });

    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->unsignedBigInteger('role_id')->nullable();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->unsignedBigInteger('department_id')->nullable();
        $table->string('phone_number')->nullable();
        $table->text('preferences')->nullable();
        $table->timestamps();
    });

    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->string('expense_number')->unique();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('department_id');
        $table->unsignedBigInteger('created_by');
        $table->unsignedBigInteger('approved_by')->nullable();
        $table->unsignedBigInteger('issued_by')->nullable();
        $table->unsignedBigInteger('checked_by')->nullable();
        $table->string('status')->default('submitted');
        $table->date('expense_date');
        $table->string('category');
        $table->string('sub_category')->nullable();
        $table->string('payment_method');
        $table->string('reference_number')->nullable();
        $table->decimal('amount', 15, 2);
        $table->boolean('vat_included')->default(false);
        $table->decimal('vat_rate', 8, 2)->default(0);
        $table->decimal('vat_amount', 15, 2)->default(0);
        $table->decimal('net_amount', 15, 2)->default(0);
        $table->timestamps();
    });

    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->date('invoice_date')->nullable();
        $table->string('invoice_number')->nullable();
        $table->string('client_name')->nullable();
        $table->string('status')->nullable();
        $table->decimal('subtotal', 15, 2)->default(0);
        $table->decimal('tax_amount', 15, 2)->default(0);
        $table->decimal('total_amount', 15, 2)->default(0);
        $table->unsignedBigInteger('created_by')->nullable();
        $table->timestamps();
    });

    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('payment_direction')->nullable();
        $table->decimal('amount', 15, 2)->default(0);
        $table->decimal('exchange_rate', 15, 6)->default(1);
        $table->timestamps();
        $table->softDeletes();
    });
});

afterEach(function () {
    Schema::dropIfExists('payments');
    Schema::dropIfExists('invoices');
    Schema::dropIfExists('expenses');
    Schema::dropIfExists('users');
    Schema::dropIfExists('departments');
    Schema::dropIfExists('companies');
    Schema::dropIfExists('roles');
    Carbon::setTestNow();
});

function makeAdminUser(Company $company): User
{
    $role = Role::query()->create([
        'name' => 'Admin',
        'description' => 'Admin',
    ]);

    return User::query()->create([
        'name' => 'Admin User',
        'email' => 'admin'.uniqid().'@example.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
        'company_id' => $company->id,
    ]);
}

function makeExpense(Company $company, Department $department, User $user, string $date, float $amount, string $number): Expense
{
    return Expense::query()->create([
        'expense_number' => $number,
        'company_id' => $company->id,
        'department_id' => $department->id,
        'created_by' => $user->id,
        'status' => 'submitted',
        'expense_date' => $date,
        'category' => 'Operations',
        'payment_method' => 'cash',
        'amount' => $amount,
        'vat_amount' => 0,
        'net_amount' => $amount,
    ]);
}

test('company filter returns only that company expenses', function () {
    $companyA = Company::query()->create(['name' => 'Alpha Co', 'country' => 'TZ', 'revenue' => 0, 'status' => 'Active']);
    $companyB = Company::query()->create(['name' => 'Beta Co', 'country' => 'TZ', 'revenue' => 0, 'status' => 'Active']);
    $deptA = Department::query()->create(['company_id' => $companyA->id, 'name' => 'Ops']);
    $deptB = Department::query()->create(['company_id' => $companyB->id, 'name' => 'Ops']);
    $user = makeAdminUser($companyA);

    makeExpense($companyA, $deptA, $user, '2026-08-05', 1000, 'EXP-A1');
    makeExpense($companyB, $deptB, $user, '2026-08-05', 2000, 'EXP-B1');

    $response = $this->actingAs($user)->post(route('reports'), [
        'report_type' => 'expenses',
        'scope' => 'company',
        'company_id' => $companyA->id,
        'date_filter' => 'this_month',
    ]);

    $response->assertOk();
    $response->assertViewHas('totals', fn ($totals) => (int) $totals['expense_count'] === 1 && (float) $totals['gross_amount'] === 1000.0);
    $response->assertViewHas('expenseRows', fn ($rows) => $rows->count() === 1 && $rows->first()['expense_number'] === 'EXP-A1');
});

test('period filter returns only expenses in selected period', function () {
    $company = Company::query()->create(['name' => 'Alpha Co', 'country' => 'TZ', 'revenue' => 0, 'status' => 'Active']);
    $dept = Department::query()->create(['company_id' => $company->id, 'name' => 'Ops']);
    $user = makeAdminUser($company);

    makeExpense($company, $dept, $user, '2026-08-05', 1000, 'EXP-AUG');
    makeExpense($company, $dept, $user, '2026-07-15', 500, 'EXP-JUL');

    $response = $this->actingAs($user)->post(route('reports'), [
        'report_type' => 'expenses',
        'scope' => 'all',
        'date_filter' => 'this_month',
    ]);

    $response->assertOk();
    $response->assertViewHas('totals', fn ($totals) => (int) $totals['expense_count'] === 1 && (float) $totals['gross_amount'] === 1000.0);
    $response->assertViewHas('expenseRows', fn ($rows) => $rows->count() === 1 && $rows->first()['expense_number'] === 'EXP-AUG');
});

test('company and period filters combine', function () {
    $companyA = Company::query()->create(['name' => 'Alpha Co', 'country' => 'TZ', 'revenue' => 0, 'status' => 'Active']);
    $companyB = Company::query()->create(['name' => 'Beta Co', 'country' => 'TZ', 'revenue' => 0, 'status' => 'Active']);
    $deptA = Department::query()->create(['company_id' => $companyA->id, 'name' => 'Ops']);
    $deptB = Department::query()->create(['company_id' => $companyB->id, 'name' => 'Ops']);
    $user = makeAdminUser($companyA);

    makeExpense($companyA, $deptA, $user, '2026-08-05', 1000, 'EXP-A-AUG');
    makeExpense($companyA, $deptA, $user, '2026-07-05', 300, 'EXP-A-JUL');
    makeExpense($companyB, $deptB, $user, '2026-08-05', 2000, 'EXP-B-AUG');

    $response = $this->actingAs($user)->post(route('reports'), [
        'report_type' => 'expenses',
        'scope' => 'company',
        'company_id' => $companyA->id,
        'date_filter' => 'custom',
        'start_date' => '2026-08-01',
        'end_date' => '2026-08-31',
    ]);

    $response->assertOk();
    $response->assertViewHas('totals', fn ($totals) => (int) $totals['expense_count'] === 1 && (float) $totals['gross_amount'] === 1000.0);
    $response->assertViewHas('expenseRows', fn ($rows) => $rows->count() === 1 && $rows->first()['expense_number'] === 'EXP-A-AUG');
});

test('equity preview and export links include company filter query string', function () {
    $company = Company::query()->create(['name' => 'Goodness Mining', 'country' => 'TZ', 'revenue' => 0, 'status' => 'Active']);
    $user = makeAdminUser($company);

    $response = $this->actingAs($user)->post(route('reports'), [
        'report_type' => 'equity',
        'scope' => 'company',
        'company_id' => $company->id,
        'date_filter' => 'this_year',
    ]);

    $response->assertOk();
    $response->assertSee('/equity-statement?', false);
    $response->assertSee('company_id='.$company->id, false);
    $response->assertSee('scope=company', false);
    $response->assertSee('/equity-statement-export?', false);
});
