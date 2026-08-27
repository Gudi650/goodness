<?php

namespace App\Services;

use App\Http\Controllers\IncomeStatement;
use App\Models\BankTransactions;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\User;
use App\Models\VirtualAccounts;
use App\Services\CashFlow\CashFlowReportService;
use App\Support\ReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CeoWeeklyDigestService
{
    /**
     * Previous calendar week (Mon–Sun), Africa/Dar_es_Salaam.
     * Open items (CEO queue, overdue) are current, not limited to that week.
     */
    public function build(): array
    {
        [$start, $end] = $this->lastWeek();

        $cashIn = $this->bankSum('income', $start, $end);
        $cashOut = $this->bankSum('expense', $start, $end);
        $salesCollected = (float) Invoice::query()
            ->where('status', 'paid')
            ->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
            ->sum('total_amount');

        $issuedExpenses = (float) Expense::query()
            ->where('status', 'issued')
            ->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $pending = Expense::query()->where('status', 'checked');
        $overdue = Invoice::query()->where('status', 'overdue');

        return [
            'week_start' => $start->format('j M Y'),
            'week_end' => $end->format('j M Y'),
            'cash_in' => $cashIn,
            'cash_out' => $cashOut,
            'closing_cash' => (float) VirtualAccounts::sum('balance'),
            'sales_collected' => $salesCollected,
            'net_income' => $salesCollected - $issuedExpenses,
            'pending_ceo_count' => $pending->count(),
            'pending_ceo_amount' => (float) (clone $pending)->sum('amount'),
            'overdue_count' => $overdue->count(),
            'overdue_amount' => (float) (clone $overdue)->sum('total_amount'),
        ];
    }

    /**
     * Year-to-date PDFs, all companies. Body of the email stays last week.
     *
     * @return array<int, array{name: string, bytes: string}>
     */
    public function pdfs(): array
    {
        ReportFilters::reset();
        ReportFilters::use(dateFilter: 'this_year');

        try {
            $cashFlow = Pdf::loadView('reports.trueCashFlow', app(CashFlowReportService::class)->build())
                ->setPaper('a4', 'portrait')
                ->output();

            $income = Pdf::loadView('reports.income_statement', array_merge(
                app(IncomeStatement::class)->reportData(),
                ['showActions' => false],
            ))
                ->setPaper('a4', 'portrait')
                ->output();

            return [
                ['name' => 'cash-flow.pdf', 'bytes' => $cashFlow],
                ['name' => 'income-statement.pdf', 'bytes' => $income],
            ];
        } finally {
            ReportFilters::reset();
        }
    }

    /**
     * @return Collection<int, User>
     */
    public function recipients(): Collection
    {
        return User::query()
            ->whereHas('role', fn ($q) => $q->where('name', 'CEO'))
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    protected function lastWeek(): array
    {
        $now = Carbon::now('Africa/Dar_es_Salaam');

        return [
            $now->copy()->subWeek()->startOfWeek(Carbon::MONDAY)->startOfDay(),
            $now->copy()->subWeek()->endOfWeek(Carbon::SUNDAY)->endOfDay(),
        ];
    }

    protected function bankSum(string $type, Carbon $start, Carbon $end): float
    {
        return (float) BankTransactions::query()
            ->where('transaction_type', $type)
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->sum(fn (BankTransactions $row) => abs((float) $row->affecting_balance));
    }
}
