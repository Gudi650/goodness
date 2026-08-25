<?php

namespace App\Services\Finance\BalanceSheet;

use App\Models\Loan;
use App\Models\Product;
use App\Models\VirtualAccounts;
use App\Support\ReportFilters;

class CurrentAssetsService
{
    public function __construct()
    {
        //
    }

    public function getCurrentAssets()
    {
        return [
            'inventory_assets' => $this->getInventoryAssets(),
            'cash_and_bank_balances' => $this->getCashAndBankBalances(),
            'loan_receivables' => $this->getLoanReceivables(current: true),
        ];
    }

    protected function getCashAndBankBalances()
    {
        $cashQuery = VirtualAccounts::where('balance', '>', 0);
        ReportFilters::current()->applyCompany($cashQuery);

        return $cashQuery->get()
            ->map(function ($account) {
                return [
                    'name' => $account->account_name,
                    'amount' => $account->balance,
                    'type' => 'dr',
                ];
            });
    }

    protected function getInventoryAssets()
    {
        $inventoryQuery = Product::where('stock', '>', 0);
        ReportFilters::current()->applyCompany($inventoryQuery);

        return $inventoryQuery->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'amount' => $product->stock * $product->cost_per_unit,
                    'type' => 'dr',
                ];
            });
    }

    /**
     * Inter-company / employee loans owed to this company (current portion).
     */
    protected function getLoanReceivables(bool $current)
    {
        $companyId = ReportFilters::current()->resolveCompanyId();

        return Loan::query()
            ->with(['counterpartyCompany', 'employee'])
            ->asReceivable($companyId)
            ->currentMaturity($current)
            ->get()
            ->map(function (Loan $loan) {
                $counterparty = $loan->isIntercompany()
                    ? ($loan->counterpartyCompany?->name ?? 'Inter-company')
                    : ($loan->employee?->name ?? 'Employee');

                return [
                    'name' => trim(($loan->code ? $loan->code.' — ' : '').'Receivable: '.$counterparty),
                    'amount' => (float) $loan->outstanding_balance,
                    'type' => 'dr',
                ];
            });
    }
}
