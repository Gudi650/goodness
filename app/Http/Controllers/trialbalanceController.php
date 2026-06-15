<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TrialBalanceController extends Controller
{
    public function exportPdf()
    {
        // Replace this array with your Eloquent Query (e.g., Account::all())
        $accounts = [
            ['name' => 'Purchases A/c', 'type' => 'dr', 'amount' => 45000],
            ['name' => 'Sales A/c', 'type' => 'cr', 'amount' => 85000],
            ['name' => 'Purchase Return A/c', 'type' => 'cr', 'amount' => 1200],
            ['name' => 'Sales Return A/c', 'type' => 'dr', 'amount' => 1500],
            ['name' => 'Cash A/c', 'type' => 'dr', 'amount' => 12500],
            ['name' => 'Bank A/c', 'type' => 'dr', 'amount' => 28000],
            ['name' => 'Capital A/c', 'type' => 'cr', 'amount' => 50000],
            ['name' => 'Salaries A/c', 'type' => 'dr', 'amount' => 8000],
            ['name' => 'Furniture A/c', 'type' => 'dr', 'amount' => 15000],
            ['name' => 'Bills Payable A/c', 'type' => 'cr', 'amount' => 4200],
            ['name' => 'Bills Receivable A/c', 'type' => 'dr', 'amount' => 6000],
            ['name' => 'Debtors A/c', 'type' => 'dr', 'amount' => 18500],
            ['name' => 'Creditors A/c', 'type' => 'cr', 'amount' => 11200],
            ['name' => 'Drawings A/c', 'type' => 'dr', 'amount' => 3000],
            ['name' => 'Loan A/c', 'type' => 'cr', 'amount' => 20000],
            ['name' => 'Interest Received A/c', 'type' => 'cr', 'amount' => 600],
            ['name' => 'Interest Paid A/c', 'type' => 'dr', 'amount' => 400],
            ['name' => 'Carriage Inward and Outward A/c', 'type' => 'dr', 'amount' => 1100],
            ['name' => 'Opening stock A/c', 'type' => 'dr', 'amount' => 14000],
            ['name' => 'Machinery A/c', 'type' => 'dr', 'amount' => 35000],
            ['name' => 'Prepaid Expenses A/c', 'type' => 'dr', 'amount' => 900],
            ['name' => 'Outstanding Expenses A/c', 'type' => 'cr', 'amount' => 1300],
            ['name' => 'Discount Received A/c', 'type' => 'cr', 'amount' => 800],
            ['name' => 'Discount Allowed A/c', 'type' => 'dr', 'amount' => 500],
        ];

        $totalDr = 0;
        $totalCr = 0;

        foreach ($accounts as $account) {
            if ($account['type'] === 'dr') {
                $totalDr += $account['amount'];
            } else {
                $totalCr += $account['amount'];
            }
        }

        $pdf = Pdf::loadView('reports.trial_balance', compact('accounts', 'totalDr', 'totalCr'));
        return $pdf->stream('trial_balance.pdf');
    }
}