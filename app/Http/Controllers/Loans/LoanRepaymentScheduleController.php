<?php

namespace App\Http\Controllers\Loans;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanRepaymentSchedule;
use App\Models\VirtualAccounts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class LoanRepaymentScheduleController extends Controller
{
    public function regenerate(Loan $loan): RedirectResponse
    {
        $loan->generateSchedule();

        return back()->with('success', "Repayment schedule regenerated for {$loan->code}.");
    }

    /**
     * Marks installment paid, reduces outstanding principal, and deducts
     * the full installment from the loan's receiving bank account.
     */
    public function markPaid(LoanRepaymentSchedule $schedule): RedirectResponse
    {
        if ($schedule->status === 'Paid') {
            return back()->with('error', "Installment #{$schedule->installment_number} is already paid.");
        }

        $loan = $schedule->loan;

        if (empty($loan->bank_id)) {
            return back()->with('error', "Loan {$loan->code} has no bank account. Assign one before marking payments.");
        }

        $amount = (float) $schedule->total_installment;

        try {
            DB::transaction(function () use ($schedule, $loan, $amount) {
                $bank = VirtualAccounts::query()->lockForUpdate()->findOrFail($loan->bank_id);

                if ((float) $bank->balance < $amount) {
                    throw new \RuntimeException(
                        "Insufficient bank balance (TZS ".number_format((float) $bank->balance).") to pay installment #{$schedule->installment_number} (TZS ".number_format($amount).")."
                    );
                }

                $bank->decrement('balance', $amount);
                $schedule->update(['status' => 'Paid']);
                $loan->decrement('outstanding_balance', $schedule->principal_portion);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Installment #{$schedule->installment_number} marked as paid. TZS ".number_format($amount)." deducted from bank.");
    }

    public function markOverdueInstallments(): void
    {
        LoanRepaymentSchedule::where('status', 'Pending')
            ->whereDate('due_date', '<', now())
            ->update(['status' => 'Overdue']);
    }
}
