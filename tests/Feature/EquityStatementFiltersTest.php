<?php

use App\Http\Controllers\CashFlowController;
use App\Models\Company;
use App\Models\EquityDistribution;
use App\Models\SharePremuims;
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
        $table->decimal('revenue', 15, 2)->default(0);
        $table->string('status')->default('Active');
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

    Schema::create('share_premuims', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('shares_issued')->default(0);
        $table->decimal('nominal_value', 15, 2)->default(0);
        $table->decimal('issue_price', 15, 2)->default(0);
        $table->decimal('premium_per_share', 15, 2)->default(0);
        $table->decimal('total_premium', 20, 2)->default(0);
        $table->text('notes')->nullable();
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

    Schema::create('asset_revaluations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->decimal('surplus', 15, 2)->default(0);
        $table->date('date_of_revaluation')->nullable();
        $table->timestamps();
    });

    Schema::create('create_assets', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('status')->default('Active');
        $table->decimal('original_value', 20, 2)->default(0);
        $table->decimal('current_value', 20, 2)->default(0);
        $table->date('disposal_date')->nullable();
        $table->decimal('disposal_proceeds', 20, 2)->nullable();
        $table->decimal('disposal_carrying_value', 20, 2)->nullable();
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

    Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('bank_id')->nullable();
        $table->string('code')->unique();
        $table->string('lender')->nullable();
        $table->decimal('principal', 15, 2)->default(0);
        $table->decimal('interest_rate', 5, 2)->default(0);
        $table->string('interest_type')->default('Flat');
        $table->unsignedInteger('term_months')->default(1);
        $table->date('disbursement_date')->nullable();
        $table->boolean('is_disbursed')->default(false);
        $table->date('start_date')->nullable();
        $table->date('maturity_date')->nullable();
        $table->decimal('outstanding_balance', 15, 2)->default(0);
        $table->string('status')->default('Active');
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('loan_repayment_schedules', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('loan_id');
        $table->unsignedInteger('installment_number')->default(1);
        $table->date('due_date')->nullable();
        $table->decimal('principal_portion', 15, 2)->default(0);
        $table->decimal('interest_portion', 15, 2)->default(0);
        $table->decimal('total_installment', 15, 2)->default(0);
        $table->string('status')->default('Pending');
        $table->timestamps();
    });
});

afterEach(function () {
    foreach ([
        'loan_repayment_schedules', 'loans', 'expenses', 'invoices',
        'create_assets', 'asset_revaluations', 'dividends', 'share_premuims',
        'equity_distributions', 'companies',
    ] as $table) {
        Schema::dropIfExists($table);
    }

    ReportFilters::reset();
    Carbon::setTestNow();
});

function seedEquityAt(array $attributes, string $at): void
{
    $row = EquityDistribution::query()->create($attributes);
    $row->forceFill([
        'created_at' => Carbon::parse($at),
        'updated_at' => Carbon::parse($at),
    ])->save();
}

function seedPremiumAt(array $attributes, string $at): void
{
    $row = SharePremuims::query()->create($attributes);
    $row->forceFill([
        'created_at' => Carbon::parse($at),
        'updated_at' => Carbon::parse($at),
    ])->save();
}

function seedEquityCompanies(): array
{
    $parent = Company::query()->create(['name' => 'Goodness Group', 'country' => 'TZ', 'status' => 'Active']);
    $mining = Company::query()->create(['name' => 'Goodness Mining', 'country' => 'TZ', 'status' => 'Active']);

    seedEquityAt([
        'company_id' => $parent->id,
        'shareholder' => 'Owner A',
        'shares' => 1,
        'value_held' => 4000,
    ], '2025-06-01');
    seedEquityAt([
        'company_id' => $mining->id,
        'shareholder' => 'Owner A',
        'shares' => 1,
        'value_held' => 300,
    ], '2025-06-01');
    seedEquityAt([
        'company_id' => $mining->id,
        'shareholder' => 'Owner B',
        'shares' => 1,
        'value_held' => 100,
    ], '2026-03-01');

    seedPremiumAt([
        'company_id' => $mining->id,
        'shares_issued' => 10,
        'nominal_value' => 1,
        'issue_price' => 2,
        'premium_per_share' => 1,
        'total_premium' => 50,
    ], '2026-03-01');

    return compact('parent', 'mining');
}

function invokeEquityReportData(): array
{
    $controller = app(CashFlowController::class);
    $method = new ReflectionMethod($controller, 'buildReportData');
    $method->setAccessible(true);

    return $method->invoke($controller);
}

function invokeShareCapital(?int $companyId, int $year): float
{
    $controller = app(CashFlowController::class);
    $method = new ReflectionMethod($controller, 'getShareCapital');
    $method->setAccessible(true);

    return (float) $method->invoke($controller, $companyId, $year);
}

test('equity statement single company share capital matches that company only', function () {
    ['parent' => $parent, 'mining' => $mining] = seedEquityCompanies();

    ReportFilters::boot(request()->merge([
        'scope' => 'company',
        'company_id' => $mining->id,
        'date_filter' => 'this_year',
    ]));

    expect(invokeShareCapital($mining->id, 2026))->toBe(400.0)
        ->and(invokeShareCapital($parent->id, 2026))->toBe(4000.0);

    $data = invokeEquityReportData();
    $balanceRow = collect($data['rows'])->firstWhere('label', 'Balance at 31 Dec 2026:');

    expect($data['company'])->toBe('Goodness Mining')
        ->and((float) $balanceRow['values'][0])->toBe(400.0);
});

test('equity statement all companies share capital sums every company', function () {
    seedEquityCompanies();

    ReportFilters::boot(request()->merge([
        'scope' => 'all',
        'date_filter' => 'this_year',
    ]));

    expect(invokeShareCapital(null, 2026))->toBe(4400.0);

    $data = invokeEquityReportData();
    $balanceRow = collect($data['rows'])->firstWhere('label', 'Balance at 31 Dec 2026:');

    expect($data['company'])->toBe('All Companies')
        ->and((float) $balanceRow['values'][0])->toBe(4400.0);
});

test('equity statement issue of shares total is capital plus premium issued only', function () {
    ['mining' => $mining] = seedEquityCompanies();

    ReportFilters::boot(request()->merge([
        'scope' => 'company',
        'company_id' => $mining->id,
        'date_filter' => 'this_year',
    ]));

    $data = invokeEquityReportData();
    $issueRow = collect($data['rows'])->firstWhere('label', 'Issue of shares');

    // Mining: capital 300 in 2025 + 100 in 2026; premium 50 issued in 2026
    expect((float) $issueRow['values'][0])->toBe(100.0)
        ->and((float) $issueRow['values'][1])->toBe(50.0)
        ->and((float) $issueRow['values'][4])->toBe(150.0);
});
