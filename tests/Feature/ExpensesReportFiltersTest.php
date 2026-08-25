<?php

use App\Http\Controllers\ExpensesReport;
use App\Models\Company;
use App\Models\Expense;
use App\Support\ReportFilters;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-08-13'));
    ReportFilters::reset();

    Schema::create('companies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('country')->nullable();
        $table->string('status')->default('Active');
        $table->timestamps();
    });

    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->string('expense_number')->nullable();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->unsignedBigInteger('department_id')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();
        $table->string('status')->nullable();
        $table->date('expense_date')->nullable();
        $table->string('category')->nullable();
        $table->decimal('amount', 15, 2)->default(0);
        $table->decimal('vat_amount', 15, 2)->default(0);
        $table->decimal('net_amount', 15, 2)->default(0);
        $table->boolean('vat_included')->default(false);
        $table->timestamps();
    });
});

afterEach(function () {
    Schema::dropIfExists('expenses');
    Schema::dropIfExists('companies');
    ReportFilters::reset();
    Carbon::setTestNow();
});

function invokeExpenseReport(array $query = []): array
{
    $controller = app(ExpensesReport::class);
    $method = new ReflectionMethod($controller, 'reportData');
    $method->setAccessible(true);

    return $method->invoke($controller, request()->merge($query));
}

test('expense report company filter returns only that company expenses in period', function () {
    $mining = Company::query()->create(['name' => 'Goodness Mining', 'status' => 'Active']);
    $parent = Company::query()->create(['name' => 'Goodness Group', 'status' => 'Active']);

    Expense::query()->create([
        'expense_number' => 'EXP-M1',
        'company_id' => $mining->id,
        'status' => 'issued',
        'expense_date' => '2026-08-05',
        'amount' => 100,
        'net_amount' => 100,
    ]);
    Expense::query()->create([
        'expense_number' => 'EXP-M2',
        'company_id' => $mining->id,
        'status' => 'issued',
        'expense_date' => '2026-08-10',
        'amount' => 50,
        'net_amount' => 50,
    ]);
    Expense::query()->create([
        'expense_number' => 'EXP-P1',
        'company_id' => $parent->id,
        'status' => 'issued',
        'expense_date' => '2026-08-08',
        'amount' => 999,
        'net_amount' => 999,
    ]);
    Expense::query()->create([
        'expense_number' => 'EXP-OLD',
        'company_id' => $mining->id,
        'status' => 'issued',
        'expense_date' => '2026-07-01',
        'amount' => 40,
        'net_amount' => 40,
    ]);

    $data = invokeExpenseReport([
        'scope' => 'company',
        'company_id' => $mining->id,
        'date_filter' => 'this_month',
    ]);

    expect($data['reportCompanyName'])->toBe('Goodness Mining')
        ->and($data['expenses']->pluck('expense_number')->all())->toBe(['EXP-M2', 'EXP-M1'])
        ->and($data['totals']['count'])->toBe(2)
        ->and($data['totals']['gross'])->toBe(150.0);
});

test('expense report all companies includes every company in period', function () {
    $mining = Company::query()->create(['name' => 'Goodness Mining', 'status' => 'Active']);
    $parent = Company::query()->create(['name' => 'Goodness Group', 'status' => 'Active']);

    Expense::query()->create([
        'expense_number' => 'EXP-M1',
        'company_id' => $mining->id,
        'expense_date' => '2026-08-05',
        'amount' => 100,
        'net_amount' => 100,
    ]);
    Expense::query()->create([
        'expense_number' => 'EXP-P1',
        'company_id' => $parent->id,
        'expense_date' => '2026-08-08',
        'amount' => 200,
        'net_amount' => 200,
    ]);

    $data = invokeExpenseReport([
        'scope' => 'all',
        'date_filter' => 'this_month',
    ]);

    expect($data['reportCompanyName'])->toBe('All Companies')
        ->and($data['expenses']->count())->toBe(2)
        ->and($data['totals']['gross'])->toBe(300.0);
});
