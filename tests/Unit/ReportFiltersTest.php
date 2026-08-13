<?php

use App\Support\ReportFilters;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-08-10'));
    ReportFilters::reset();
    Schema::create('filter_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->date('item_date');
        $table->decimal('amount', 15, 2)->default(0);
    });
});

afterEach(function () {
    Schema::dropIfExists('filter_items');
    ReportFilters::reset();
    Carbon::setTestNow();
});

test('report filters apply company and date together', function () {
    DB::table('filter_items')->insert([
        ['company_id' => 1, 'item_date' => '2026-08-05', 'amount' => 100],
        ['company_id' => 1, 'item_date' => '2026-07-05', 'amount' => 50],
        ['company_id' => 2, 'item_date' => '2026-08-05', 'amount' => 200],
    ]);

    $filters = ReportFilters::boot(request()->merge([
        'scope' => 'company',
        'company_id' => 1,
        'date_filter' => 'this_month',
    ]));

    $query = DB::table('filter_items');
    $filters->apply($query, 'item_date');

    expect((float) $query->sum('amount'))->toBe(100.0);
});

test('report filters all companies uses period only', function () {
    DB::table('filter_items')->insert([
        ['company_id' => 1, 'item_date' => '2026-08-05', 'amount' => 100],
        ['company_id' => 2, 'item_date' => '2026-08-05', 'amount' => 200],
        ['company_id' => 2, 'item_date' => '2026-07-05', 'amount' => 30],
    ]);

    $filters = ReportFilters::boot(request()->merge([
        'scope' => 'all',
        'date_filter' => 'this_month',
    ]));

    $query = DB::table('filter_items');
    $filters->apply($query, 'item_date');

    expect((float) $query->sum('amount'))->toBe(300.0);
});

test('report filters query string includes company and period', function () {
    $filters = ReportFilters::boot(request()->merge([
        'scope' => 'company',
        'company_id' => 9,
        'date_filter' => 'custom',
        'start_date' => '2026-01-01',
        'end_date' => '2026-01-31',
    ]));

    expect($filters->queryString())->toContain('company_id=9')
        ->and($filters->queryString())->toContain('date_filter=custom')
        ->and($filters->year())->toBe(2026);
});

test('report filters company-only does not date-slice vat style queries', function () {
    DB::table('filter_items')->insert([
        ['company_id' => 1, 'item_date' => '2026-01-05', 'amount' => 40],
        ['company_id' => 1, 'item_date' => '2026-08-05', 'amount' => 60],
        ['company_id' => 2, 'item_date' => '2026-08-05', 'amount' => 200],
    ]);

    $filters = ReportFilters::boot(request()->merge([
        'scope' => 'company',
        'company_id' => 1,
        'date_filter' => 'this_month',
    ]));

    $query = DB::table('filter_items');
    $filters->applyCompany($query);

    expect((float) $query->sum('amount'))->toBe(100.0);
});

test('report filters scope all ignores company id from request', function () {
    $filters = ReportFilters::boot(request()->merge([
        'scope' => 'all',
        'company_id' => 1,
        'date_filter' => 'this_year',
    ]));

    expect($filters->scope)->toBe('all')
        ->and($filters->companyId)->toBeNull()
        ->and($filters->resolveCompanyId())->toBeNull();
});

test('topbar active company syncs to company scope when request has no filters', function () {
    session(['active_company_id' => 15]);

    $filters = ReportFilters::boot(request());

    expect($filters->scope)->toBe('company')
        ->and($filters->companyId)->toBe(15)
        ->and($filters->resolveCompanyId())->toBe(15);
});

test('topbar all companies syncs to all scope when active company is empty', function () {
    session()->forget('active_company_id');
    session(['report_filters' => ['scope' => 'company', 'company_id' => 7]]);

    $filters = ReportFilters::boot(request());

    expect($filters->scope)->toBe('all')
        ->and($filters->companyId)->toBeNull()
        ->and($filters->resolveCompanyId())->toBeNull();
});

test('goodness group parent company is not remapped to all scope', function () {
    Schema::create('companies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    $parentId = DB::table('companies')->insertGetId([
        'name' => 'Goodness Group',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $filters = ReportFilters::boot(request()->merge([
        'scope' => 'company',
        'company_id' => $parentId,
        'date_filter' => 'this_year',
    ]));

    expect($filters->scope)->toBe('company')
        ->and($filters->companyId)->toBe($parentId)
        ->and($filters->displayCompanyName())->toBe('Goodness Group (Parent)');

    Schema::dropIfExists('companies');
});

test('all companies scope includes parent and subsidiaries', function () {
    DB::table('filter_items')->insert([
        ['company_id' => 7, 'item_date' => '2026-08-05', 'amount' => 100],
        ['company_id' => 15, 'item_date' => '2026-08-05', 'amount' => 400],
        ['company_id' => 10, 'item_date' => '2026-08-05', 'amount' => 200],
    ]);

    $filters = ReportFilters::boot(request()->merge([
        'scope' => 'all',
        'date_filter' => 'this_month',
    ]));

    $query = DB::table('filter_items');
    $filters->applyCompany($query);

    expect((float) $query->sum('amount'))->toBe(700.0)
        ->and($filters->displayCompanyName())->toBe('All Companies');
});

test('parent-only company filter excludes subsidiaries', function () {
    DB::table('filter_items')->insert([
        ['company_id' => 7, 'item_date' => '2026-08-05', 'amount' => 100],
        ['company_id' => 15, 'item_date' => '2026-08-05', 'amount' => 400],
        ['company_id' => 10, 'item_date' => '2026-08-05', 'amount' => 200],
    ]);

    $filters = ReportFilters::boot(request()->merge([
        'scope' => 'company',
        'company_id' => 15,
        'date_filter' => 'this_month',
    ]));

    $query = DB::table('filter_items');
    $filters->applyCompany($query);

    expect((float) $query->sum('amount'))->toBe(400.0);
});
