<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Invoice;

class NetIncome
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Calculate Net Income
     */
    public function calculateNetIncome(?int $companyId = null, ?int $year = null): float
    {
        // Total Revenue
        $totalRevenue = $this->getRevenues($companyId, $year)->sum('total_amount');

        // Expenses grouped by category
        $totalExpensesByCategory = $this->getExpenseStatement($companyId, $year);

        // Detect COGS category dynamically
        $cogsCategory = $totalExpensesByCategory->keys()->first(function ($key) {
            $k = strtolower((string) $key);

            return str_contains($k, 'cogs')
                || str_contains($k, 'cost of good sold')
                || str_contains($k, 'cost of goods sold');
        });

        // Categories excluded from operating expenses
        $excludedCategories = collect([$cogsCategory, 'Investment'])
            ->filter()
            ->values();

        // Total Operating Expenses (excluding COGS and Investment)
        $totalOperatingExpenses = $totalExpensesByCategory
            ->reject(function ($items, $category) use ($excludedCategories) {
                return $excludedCategories->contains($category)
                    || str_contains(strtolower((string) $category), 'investment');
            })
            ->flatten()
            ->sum();

        // Total COGS
        $totalCOGS = $cogsCategory
            ? ($totalExpensesByCategory->get($cogsCategory, collect())->sum() ?? 0)
            : 0;

        // Gross Profit
        $grossProfit = $totalRevenue - $totalCOGS;

        // Operating Income
        $operatingIncome = $grossProfit - $totalOperatingExpenses;

        // Other income/expenses (future use)
        $otherItemsTotal = 0;

        // Profit before tax
        $preTaxIncome = $operatingIncome + $otherItemsTotal;

        // Tax Expense (18%)
        $taxExpense = $preTaxIncome * 0.18;

        // Net Income
        $netIncome = $preTaxIncome - $taxExpense;

        return (float) $netIncome;
    }

    /**
     * Get Paid Revenues
     */
    protected function getRevenues(?int $companyId = null, ?int $year = null)
    {
        $revenues = Invoice::query()
            ->where('status', 'paid');

        if ($companyId) {
            $revenues->where('company_id', $companyId);
        }

        if ($year) {
            $revenues->whereYear('created_at', $year);
        }

        return $revenues->get();
    }

    /**
     * Revenue grouped by category
     */
    protected function getTotalRevenuesByCategory(?int $companyId = null, ?int $year = null)
    {
        return $this->getRevenues($companyId, $year)
            ->groupBy('category')
            ->map(function ($group) {
                return $group->sum('total_amount');
            });
    }

    /**
     * Get Issued Expenses
     */
    protected function getExpenses(?int $companyId = null, ?int $year = null)
    {
        $expenses = Expense::query()
            ->where('status', 'issued');

        if ($companyId) {
            $expenses->where('company_id', $companyId);
        }

        if ($year) {
            $expenses->whereYear('created_at', $year);
        }

        $expenses = $expenses->get();

        return $expenses->isEmpty()
            ? collect()
            : $expenses;
    }

    /**
     * Expense Statement
     */
    protected function getExpenseStatement(?int $companyId = null, ?int $year = null)
    {
        $expenses = $this->getExpenses($companyId, $year);

        $expenseStatement = $expenses
            ->groupBy(function ($expense) {
                return $expense?->category ?? 'Uncategorized_Category';
            })
            ->map(function ($categoryExpenses) {

                return $categoryExpenses
                    ->groupBy(function ($expense) {
                        return $expense?->financeItem?->item_name ?? 'Uncategorized_Sub_Category';
                    })
                    ->map(function ($itemExpenses) {
                        return $itemExpenses->sum('amount');
                    });

            });

        // Keep COGS and Operational at the top
        $orderedExpenseStatement = collect();

        foreach (['Cost of Goods Sold (COGS)', 'Operational'] as $category) {
            if ($expenseStatement->has($category)) {
                $orderedExpenseStatement->put($category, $expenseStatement->get($category));
            }
        }

        foreach ($expenseStatement as $category => $items) {
            if (! $orderedExpenseStatement->has($category)) {
                $orderedExpenseStatement->put($category, $items);
            }
        }

        return $orderedExpenseStatement;
    }
}