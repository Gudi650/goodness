<?php

use App\Http\Controllers\balanceSheetController;
use App\Models\Company;
use App\Models\EquityDistribution;
use App\Models\Role;
use App\Models\User;
use App\Models\VirtualAccounts;
use App\Support\ReportFilters;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-08-13'));
    ReportFilters::reset();

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

    Schema::create('equity_distributions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->string('shareholder')->nullable();
        $table->integer('shares')->default(0);
        $table->decimal('value_held', 15, 2)->default(0);
        $table->decimal('ownership_percentage', 8, 2)->nullable();
        $table->text('notes')->nullable();
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

    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('name')->nullable();
        $table->decimal('stock', 15, 2)->default(0);
        $table->decimal('cost_per_unit', 15, 2)->default(0);
        $table->timestamps();
    });

    Schema::create('assets_categories', function (Blueprint $table) {
        $table->id();
        $table->string('category');
        $table->timestamps();
    });

    Schema::create('create_assets', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->unsignedBigInteger('category_id')->nullable();
        $table->string('name')->nullable();
        $table->decimal('original_value', 15, 2)->default(0);
        $table->decimal('current_value', 15, 2)->default(0);
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
        $table->string('invoice_number')->nullable();
        $table->string('category')->nullable();
        $table->timestamps();
    });

    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('status')->nullable();
        $table->boolean('vat_included')->default(false);
        $table->decimal('vat_amount', 15, 2)->default(0);
        $table->decimal('amount', 15, 2)->default(0);
        $table->string('category')->nullable();
        $table->string('expense_number')->nullable();
        $table->unsignedBigInteger('finance_item_id')->nullable();
        $table->timestamps();
    });

    Schema::create('dividends', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('status')->nullable();
        $table->decimal('amount', 15, 2)->default(0);
        $table->timestamp('paid_at')->nullable();
        $table->timestamp('declared_at')->nullable();
        $table->timestamps();
    });
});

afterEach(function () {
    foreach ([
        'dividends', 'expenses', 'invoices', 'create_liabilities', 'liability_categories',
        'create_assets', 'assets_categories', 'products', 'virtual_accounts',
        'equity_distributions', 'users', 'companies', 'roles',
    ] as $table) {
        Schema::dropIfExists($table);
    }

    ReportFilters::reset();
    Carbon::setTestNow();
});

function seedBsCompanies(): array
{
    $parent = Company::query()->create(['name' => 'Goodness Group', 'country' => 'TZ', 'status' => 'Active']);
    $mining = Company::query()->create(['name' => 'Goodness Mining', 'country' => 'TZ', 'status' => 'Active']);

    EquityDistribution::query()->create([
        'company_id' => $parent->id,
        'shareholder' => 'Owner A',
        'shares' => 1,
        'value_held' => 4000,
    ]);
    EquityDistribution::query()->create([
        'company_id' => $mining->id,
        'shareholder' => 'Owner A',
        'shares' => 1,
        'value_held' => 300,
    ]);

    VirtualAccounts::query()->create([
        'company_id' => $parent->id,
        'bank_name' => 'CRDB',
        'account_name' => 'Parent Cash',
        'account_number' => 'P-1',
        'balance' => 1500,
        'status' => 'active',
    ]);
    VirtualAccounts::query()->create([
        'company_id' => $mining->id,
        'bank_name' => 'NMB',
        'account_name' => 'Mining Cash',
        'account_number' => 'M-1',
        'balance' => 200,
        'status' => 'active',
    ]);

    return compact('parent', 'mining');
}

function makeBsAdmin(Company $company): User
{
    $role = Role::query()->create(['name' => 'Admin', 'description' => 'Admin']);

    return User::query()->create([
        'name' => 'Admin',
        'email' => 'admin'.uniqid().'@example.com',
        'password' => Hash::make('password'),
        'role_id' => $role->id,
        'company_id' => $company->id,
    ]);
}

function invokeReportData(): array
{
    $controller = app(balanceSheetController::class);
    $method = new ReflectionMethod($controller, 'reportData');
    $method->setAccessible(true);

    return $method->invoke($controller);
}

test('balance sheet parent-only uses goodness group share capital and cash', function () {
    ['parent' => $parent, 'mining' => $mining] = seedBsCompanies();
    $user = makeBsAdmin($parent);

    $this->actingAs($user);
    session(['active_company_id' => $parent->id]);

    $data = invokeReportData();
    $shareCapital = collect($data['equityLiabilities']['equity'])->firstWhere('name', 'Share Capital')['amount'] ?? 0;
    $cash = collect($data['currentAssets']['cash_and_bank_balances'] ?? [])->sum('amount');

    expect($data['reportCompanyName'])->toBe('Goodness Group (Parent)')
        ->and((float) $shareCapital)->toBe(4000.0)
        ->and((float) $cash)->toBe(1500.0);
});

test('balance sheet all companies includes parent and subsidiary share capital', function () {
    ['parent' => $parent] = seedBsCompanies();
    $user = makeBsAdmin($parent);

    $this->actingAs($user);
    session()->forget('active_company_id');

    $data = invokeReportData();
    $shareCapital = collect($data['equityLiabilities']['equity'])->firstWhere('name', 'Share Capital')['amount'] ?? 0;
    $cash = collect($data['currentAssets']['cash_and_bank_balances'] ?? [])->sum('amount');

    expect($data['reportCompanyName'])->toBe('All Companies')
        ->and((float) $shareCapital)->toBe(4300.0)
        ->and((float) $cash)->toBe(1700.0);
});

test('balance sheet mining-only excludes parent figures', function () {
    ['parent' => $parent, 'mining' => $mining] = seedBsCompanies();
    $user = makeBsAdmin($parent);

    $this->actingAs($user);
    session(['active_company_id' => $mining->id]);

    $data = invokeReportData();
    $shareCapital = collect($data['equityLiabilities']['equity'])->firstWhere('name', 'Share Capital')['amount'] ?? 0;
    $cash = collect($data['currentAssets']['cash_and_bank_balances'] ?? [])->sum('amount');

    expect($data['reportCompanyName'])->toBe('Goodness Mining')
        ->and((float) $shareCapital)->toBe(300.0)
        ->and((float) $cash)->toBe(200.0);
});

test('active company store keeps goodness group as parent company scope', function () {
    ['parent' => $parent] = seedBsCompanies();
    $user = makeBsAdmin($parent);

    if (! Route::has('active-company.store')) {
        $this->markTestSkipped('active-company.store route missing');
    }

    $response = $this->actingAs($user)->post(route('active-company.store'), [
        'company_id' => $parent->id,
    ]);

    $response->assertRedirect();
    expect(session('active_company_id'))->toBe($parent->id)
        ->and(session('report_filters.scope'))->toBe('company')
        ->and((int) session('report_filters.company_id'))->toBe($parent->id);
});
