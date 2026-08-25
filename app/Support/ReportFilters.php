<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ReportFilters
{
    private static ?self $instance = null;

    public function __construct(
        public readonly string $scope = 'all',
        public readonly ?int $companyId = null,
        public readonly string $dateFilter = 'this_month',
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
    ) {}

    public static function current(): self
    {
        return self::$instance ??= self::fromSession();
    }

    public static function boot(?Request $request = null): self
    {
        $request ??= request();

        $scopeFromRequest = $request->string('scope')->toString();
        $hasCompanyInRequest = $request->filled('company_id');

        $dateFilter = $request->string('date_filter')->toString()
            ?: (string) session('report_filters.date_filter', 'this_month');
        $startDate = $request->string('start_date')->toString()
            ?: session('report_filters.start_date');
        $endDate = $request->string('end_date')->toString()
            ?: session('report_filters.end_date');

        $activeCompanyId = session('active_company_id');

        // Topbar drives company scope unless the request explicitly sets report filters.
        if ($scopeFromRequest === '' && ! $hasCompanyInRequest) {
            if ($activeCompanyId) {
                $scope = 'company';
                $companyId = (int) $activeCompanyId;
            } else {
                $scope = 'all';
                $companyId = null;
            }
        } else {
            $scope = $scopeFromRequest
                ?: (string) session('report_filters.scope', 'all');
            $companyId = $hasCompanyInRequest
                ? $request->integer('company_id')
                : (session('report_filters.company_id') ? (int) session('report_filters.company_id') : null);

            if ($scope !== 'company') {
                $companyId = null;
            }

            if (! $companyId && $scope === 'company') {
                $companyId = (int) ($activeCompanyId ?? Auth::user()?->company_id ?? 0) ?: null;
            }
        }

        $payload = [
            'scope' => $scope ?: 'all',
            'company_id' => $companyId,
            'date_filter' => $dateFilter ?: 'this_month',
            'start_date' => $startDate ?: null,
            'end_date' => $endDate ?: null,
        ];

        session(['report_filters' => $payload]);

        return self::$instance = new self(
            scope: $payload['scope'],
            companyId: $payload['company_id'],
            dateFilter: $payload['date_filter'],
            startDate: $payload['start_date'],
            endDate: $payload['end_date'],
        );
    }

    public static function reset(): void
    {
        self::$instance = null;
    }

    public static function fromSession(): self
    {
        $payload = session('report_filters', []);

        return new self(
            scope: (string) ($payload['scope'] ?? 'all'),
            companyId: isset($payload['company_id']) ? (int) $payload['company_id'] : null,
            dateFilter: (string) ($payload['date_filter'] ?? 'this_month'),
            startDate: $payload['start_date'] ?? null,
            endDate: $payload['end_date'] ?? null,
        );
    }

    public function applyCompany($query, string $column = 'company_id')
    {
        if ($this->scope === 'company' && $this->companyId) {
            $query->where($column, $this->companyId);
        }

        return $query;
    }

    public function consolidationParentCompanyId(): ?int
    {
        if (! Schema::hasTable('companies')) {
            return null;
        }

        $id = \App\Models\Company::query()
            ->where('name', 'Goodness Group')
            ->value('id');

        return $id ? (int) $id : null;
    }

    public function displayCompanyName(): string
    {
        if ($this->scope === 'company' && $this->companyId) {
            $name = \App\Models\Company::query()->whereKey($this->companyId)->value('name');

            if ($name === 'Goodness Group') {
                return 'Goodness Group (Parent)';
            }

            return $name ?: 'Company';
        }

        return 'All Companies';
    }

    public function applyDate($query, string $dateColumn)
    {
        [$start, $end] = $this->dateBounds();

        if ($start) {
            $query->whereDate($dateColumn, '>=', $start);
        }
        if ($end) {
            $query->whereDate($dateColumn, '<=', $end);
        }

        return $query;
    }

    public function apply($query, ?string $dateColumn = null, string $companyColumn = 'company_id')
    {
        $this->applyCompany($query, $companyColumn);

        if ($dateColumn) {
            $this->applyDate($query, $dateColumn);
        }

        return $query;
    }

    /** @return array{0:?string,1:?string} */
    public function dateBounds(): array
    {
        $period = $this->dateFilter;

        if ($period === 'custom' || $period === 'custome') {
            $start = $this->startDate ? Carbon::parse($this->startDate)->toDateString() : null;
            $end = $this->endDate ? Carbon::parse($this->endDate)->toDateString() : null;

            if ($start && $end && Carbon::parse($start)->greaterThan(Carbon::parse($end))) {
                [$start, $end] = [$end, $start];
            }

            return [
                $start,
                $end,
            ];
        }

        return match ($period) {
            'this_month' => [
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::now()->endOfMonth()->toDateString(),
            ],
            'last_month' => [
                Carbon::now()->subMonth()->startOfMonth()->toDateString(),
                Carbon::now()->subMonth()->endOfMonth()->toDateString(),
            ],
            'this_quarter' => [
                Carbon::now()->firstOfQuarter()->toDateString(),
                Carbon::now()->lastOfQuarter()->toDateString(),
            ],
            'this_year' => [
                Carbon::now()->startOfYear()->toDateString(),
                Carbon::now()->endOfYear()->toDateString(),
            ],
            'last_year' => [
                Carbon::now()->subYear()->startOfYear()->toDateString(),
                Carbon::now()->subYear()->endOfYear()->toDateString(),
            ],
            default => [
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::now()->endOfMonth()->toDateString(),
            ],
        };
    }

    public function resolveCompanyId(): ?int
    {
        if ($this->scope === 'company' && $this->companyId) {
            return $this->companyId;
        }

        return null;
    }

    public function year(): int
    {
        [, $end] = $this->dateBounds();

        return $end ? Carbon::parse($end)->year : now()->year;
    }

    public function queryString(): string
    {
        return http_build_query(array_filter([
            'scope' => $this->scope,
            'company_id' => $this->companyId,
            'date_filter' => $this->dateFilter,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ], fn ($v) => $v !== null && $v !== ''));
    }
}
