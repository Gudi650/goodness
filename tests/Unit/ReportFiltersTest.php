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
