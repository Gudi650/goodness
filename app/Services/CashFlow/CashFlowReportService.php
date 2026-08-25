<?php

namespace App\Services\CashFlow;

use App\Models\AssetRevaluation;
use App\Models\BankTransactions;
use App\Models\Company;
use App\Models\CreateAssets;
use App\Models\Dividends;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Loan;
use App\Models\LoanRepaymentSchedule;
use App\Models\SharePremuims;
use App\Models\VirtualAccounts;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CashFlowReportService
{
    public function build(): array
    {
        $companyId = session('active_company_id') ?? Auth::user()?->company_id;
        $years = [now()->year, now()->subYear()->year, now()->subYears(2)->year];

        return [
            'companyName' => $this->resolveCompanyName($companyId),
            'currencyUnit' => 'TZS',
            'currencySymbol' => 'TZS ',
            'years' => array_map(fn (int $year) => $this->buildYearRow($companyId, $year), $years),
            'operatingAdjustments' => [
                'Net income' => $this->series(fn (int $year) => $this->netIncome($companyId, $year), $years),
                'Depreciation and amortization' => $this->series(fn (int $year) => $this->depreciationAndAmortization($companyId, $year), $years),
                'Asset revaluation surplus' => $this->series(fn (int $year) => $this->assetRevaluationSurplus($companyId, $year), $years),
            ],
            'operatingChanges' => [
                'Cash invoices received' => $this->series(fn (int $year) => $this->invoiceCashInflows($companyId, $year), $years),
                // 'Issued expenses paid' => $this->series(fn (int $year) => $this->expenseCashOutflows($companyId, $year), $years),
                'Loan interest paid' => $this->series(fn (int $year) => $this->loanInterestOutflows($companyId, $year), $years),
            ],
            'investingActivities' => [
                'Purchase of assets' => $this->series(fn (int $year) => $this->assetPurchases($companyId, $year), $years),
                'Asset disposals' => $this->series(fn (int $year) => $this->assetDisposals($companyId, $year), $years),
            ],
            'financingActivities' => [
                'Loan disbursements' => $this->series(fn (int $year) => $this->loanDisbursements($companyId, $year), $years),
                'Loan principal repayments' => $this->series(fn (int $year) => $this->loanPrincipalRepayments($companyId, $year), $years),
                'Dividends paid' => $this->series(fn (int $year) => $this->dividendsPaid($companyId, $year), $years),
                'Share premium proceeds' => $this->series(fn (int $year) => $this->sharePremiumInflows($companyId, $year), $years),
            ],
            'supplemental' => [
                'Bank transaction net movement' => $this->series(fn (int $year) => $this->bankNetMovement($companyId, $year), $years),
                'Closing cash by virtual accounts' => $this->series(fn (int $year) => $this->closingCash($companyId, $year), $years),
            ],
        ];
    }

    protected function buildYearRow(?int $companyId, int $year): array
    {
        $openingCash = $this->openingCash($companyId, $year);
        $operatingTotal = $this->operatingCashFlow($companyId, $year);
        $investingTotal = $this->investingCashFlow($companyId, $year);
        $financingTotal = $this->financingCashFlow($companyId, $year);
        $netChange = $operatingTotal + $investingTotal + $financingTotal;

        return [
            'date_label' => Carbon::create($year, 12, 31)->format('F j, Y'),
            'beginning_balance' => $openingCash,
            'net_income' => $this->netIncome($companyId, $year),
            'operating_total' => $operatingTotal,
            'investing_total' => $investingTotal,
            'financing_total' => $financingTotal,
            'net_change' => $netChange,
            'ending_balance' => $openingCash + $netChange,
        ];
    }

    protected function series(callable $callback, array $years): array
    {
        return array_map(fn (int $year) => (float) $callback($year), $years);
    }

    protected function resolveCompanyName(?int $companyId): string
    {
        if (! $companyId) {
            return 'Company';
        }

        return Company::query()->whereKey($companyId)->value('name') ?: 'Company';
    }

    protected function openingCash(?int $companyId, int $year): float
    {
        return $this->closingCash($companyId, $year - 1);
    }

    protected function closingCash(?int $companyId, int $year): float
    {
        $query = VirtualAccounts::query()
            ->when($companyId, fn ($builder) => $builder->where('company_id', $companyId));

        return (float) $query->sum('balance');
    }

    protected function bankNetMovement(?int $companyId, int $year): float
    {
        $query = BankTransactions::query()
            ->when($companyId, fn ($builder) => $builder->where('company_id', $companyId))
            ->whereYear('created_at', $year)
            ->get();

        return (float) $query->sum(fn (BankTransactions $transaction) => $this->signedBankTransactionAmount($transaction));
    }

    protected function operatingCashFlow(?int $companyId, int $year): float
    {
        return $this->invoiceCashInflows($companyId, $year)
            - $this->expenseCashOutflows($companyId, $year)
            - $this->loanInterestOutflows($companyId, $year);
    }

    protected function investingCashFlow(?int $companyId, int $year): float
    {
        return $this->assetDisposals($companyId, $year) - $this->assetPurchases($companyId, $year);
    }

    protected function financingCashFlow(?int $companyId, int $year): float
    {
        return $this->loanDisbursements($companyId, $year)
            - $this->loanPrincipalRepayments($companyId, $year)
            - $this->dividendsPaid($companyId, $year)
            + $this->sharePremiumInflows($companyId, $year);
    }

    protected function netIncome(?int $companyId, int $year): float
    {
        return (float) app(\App\Services\NetIncome::class)->calculateNetIncome($companyId, $year);
    }

    protected function invoiceCashInflows(?int $companyId, int $year): float
    {
        $query = Invoice::query()->where('status', 'paid')->whereYear('invoice_date', $year);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return (float) $query->get()->sum(fn (Invoice $invoice) => (float) ($invoice->total_amount ?? 0) - (float) ($invoice->tax_amount ?? 0));
    }

    protected function expenseCashOutflows(?int $companyId, int $year): float
    {
        $query = Expense::query()->where('status', 'issued')->whereYear('expense_date', $year);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return (float) $query->sum('net_amount');
    }

    protected function loanInterestOutflows(?int $companyId, int $year): float
    {
        $query = LoanRepaymentSchedule::query()->where('status', 'Paid')->whereYear('updated_at', $year);

        if ($companyId) {
            $query->whereHas('loan', fn ($loanQuery) => $loanQuery->where('company_id', $companyId));
        }

        return (float) $query->sum('interest_portion');
    }

    protected function assetPurchases(?int $companyId, int $year): float
    {
        $query = CreateAssets::query()->whereYear('acquired', $year);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return (float) $query->sum('current_value');
    }

    protected function assetDisposals(?int $companyId, int $year): float
    {
        $query = CreateAssets::query()->where('status', 'Sold')->whereYear('acquired', $year);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return (float) $query->sum('current_value');
    }

    protected function loanDisbursements(?int $companyId, int $year): float
    {
        $query = Loan::query()->whereNotNull('disbursement_date')->whereYear('disbursement_date', $year);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return (float) $query->sum('principal');
    }

    protected function loanPrincipalRepayments(?int $companyId, int $year): float
    {
        $query = LoanRepaymentSchedule::query()->where('status', 'Paid')->whereYear('updated_at', $year);

        if ($companyId) {
            $query->whereHas('loan', fn ($loanQuery) => $loanQuery->where('company_id', $companyId));
        }

        return (float) $query->sum('principal_portion');
    }

    protected function dividendsPaid(?int $companyId, int $year): float
    {
        $query = Dividends::query()->where('status', 'Declared')->whereYear('paid_at', $year);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return (float) $query->sum('amount');
    }

    protected function sharePremiumInflows(?int $companyId, int $year): float
    {
        $query = SharePremuims::query()->whereYear('created_at', $year);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return (float) $query->sum('total_premium');
    }

    protected function assetRevaluationSurplus(?int $companyId, int $year): float
    {
        $query = AssetRevaluation::query()->whereYear('date_of_revaluation', $year);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return (float) $query->sum('surplus');
    }

    protected function depreciationAndAmortization(?int $companyId, int $year): float
    {
        $query = CreateAssets::query()->whereYear('acquired', '<=', $year);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return (float) $query->sum('depreciation_value');
    }

    protected function signedBankTransactionAmount(BankTransactions $transaction): float
    {
        $amount = abs((float) $transaction->affecting_balance);

        return $transaction->transaction_type === 'income' ? $amount : -$amount;
    }
}