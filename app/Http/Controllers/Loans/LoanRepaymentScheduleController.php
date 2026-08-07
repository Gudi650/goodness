<?php

namespace App\Http\Controllers\Loans;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanRepaymentSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoanRepaymentScheduleController extends Controller
{
    /**
     * Rebuilds a loan's entire schedule from its current terms. Useful if
     * a loan was created before this endpoint existed, or if you want a
     * manual "Regenerate Schedule" button rather than relying only on the
     * automatic regeneration in LoanController::update().
     */
    public function regenerate(Loan $loan): RedirectResponse
    {
        $loan->generateSchedule();

        return back()->with('success', "Repayment schedule regenerated for {$loan->code}.");
    }

    /**
     * Marks a single installment as paid. This does not itself record a
     * repayment transaction (that's the separate Repayments tab/table) —
     * it only flips this installment's status once you know it was paid,
     * and reduces the loan's outstanding balance accordingly.
     */
    public function markPaid(LoanRepaymentSchedule $schedule): RedirectResponse
    {
        $schedule->update(['status' => 'Paid']);

        $schedule->loan->decrement('outstanding_balance', $schedule->principal_portion);

        return back()->with('success', "Installment #{$schedule->installment_number} marked as paid.");
    }

    /**
     * Sweeps all Pending installments past their due date to Overdue.
     * Wire this into the console kernel schedule (php artisan schedule:run)
     * to run daily rather than calling it from a route.
     */
    public function markOverdueInstallments(): void
    {
        LoanRepaymentSchedule::where('status', 'Pending')
            ->whereDate('due_date', '<', now())
            ->update(['status' => 'Overdue']);
    }
}