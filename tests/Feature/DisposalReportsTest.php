<?php

use App\Models\CreateAssets;
use App\Models\Company;
use App\Services\Finance\AssetDisposalService;
use App\Services\NetIncome;
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
        $table->string('status')->default('Active');
        $table->timestamps();
    });
    Schema::create('create_assets', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->string('name');
        $table->unsignedBigInteger('company_id')->nullable();
        $table->string('type')->default('Fixed Asset');
        $table->string('term')->default('Long-term');
        $table->decimal('original_value', 20, 2)->default(0);
        $table->decimal('current_value', 20, 2)->default(0);
        $table->string('status')->default('Active');
        $table->date('acquired')->nullable();
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
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('loan_repayment_schedules', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('loan_id');
        $table->decimal('interest_portion', 15, 2)->default(0);
        $table->string('status')->default('Pending');
        $table->timestamps();
    });
});

afterEach(function () {
    foreach (['loan_repayment_schedules', 'loans', 'expenses', 'invoices', 'create_assets', 'companies'] as $t) {
        Schema::dropIfExists($t);
    }
    ReportFilters::reset();
    Carbon::setTestNow();
});

test('disposal gain loss and cash proceeds feed reports', function () {
    $company = Company::query()->create(['name' => 'Goodness Mining', 'status' => 'Active']);

    CreateAssets::query()->create([
        'code' => 'FA-D1',
        'name' => 'Truck',
        'company_id' => $company->id,
        'type' => 'Fixed Asset',
        'term' => 'Long-term',
        'original_value' => 5000,
        'current_value' => 0,
        'status' => 'Sold',
        'acquired' => '2024-01-01',
        'disposal_date' => '2026-08-10',
        'disposal_proceeds' => 2500,
        'disposal_carrying_value' => 3000,
    ]);

    ReportFilters::boot(request()->merge([
        'scope' => 'company',
        'company_id' => $company->id,
        'date_filter' => 'this_year',
    ]));

    $service = app(AssetDisposalService::class);

    expect($service->gainOrLoss($company->id, 2026))->toBe(-500.0)
        ->and($service->cashProceeds($company->id, 2026))->toBe(2500.0)
        ->and(app(NetIncome::class)->calculateNetIncome($company->id, 2026))->toBe(-500.0);
});
