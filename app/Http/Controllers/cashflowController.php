<?php

namespace App\Http\Controllers;

use App\Models\Dividends;
use App\Services\NetIncome;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CashFlowController extends Controller
{
    public function exportPdf()
    {
        $data = [
            'company' => 'JBC Plc.',
            'title' => 'Statement of Changes in Equity',
            'period' => '31 December 20X4',
            'scale' => '(in thousands EUR)',
            'columns' => [
                'Share capital',
                'Share premium',
                'Retained earnings',
                'Revaluation surplus (PPE)',
                'Total equity attributable to the owners of the parent',
            ],
            'rows' => [
                ['label' => 'Balance at 1 Jan 20X3', 'values' => [10000, 1100, 5240, 1000, 17340], 'strong' => true],
                ['label' => 'Changes in accounting policy', 'values' => [null, null, 660, null, 660]],
                ['label' => 'Restated balance', 'values' => [10000, 1100, 5900, 1000, 18000], 'strong' => true],
                ['label' => 'Changes in equity for 20X3:', 'section' => true],
                ['label' => 'Dividends paid', 'values' => [null, null, -3000, null, -3000], 'indent' => 1],
                ['label' => 'Profit or loss', 'values' => [null, null, 4800, null, 4800], 'indent' => 1, 'italic' => true],
                ['label' => 'Other comprehensive income', 'values' => [null, null, null, 580, 580], 'indent' => 1, 'italic' => true],
                ['label' => 'TCI for the year', 'values' => [null, null, 4800, 580, 5380], 'underline' => true],
                ['label' => 'Balance at 31 Dec 20X3:', 'values' => [10000, 1100, 7700, 1580, 20380], 'strong' => true],
                ['label' => 'Changes in equity for 20X4:', 'section' => true],
                ['label' => 'Issue of shares', 'values' => [2000, 200, null, null, 2200], 'indent' => 1],
                ['label' => 'Dividends paid', 'values' => [null, null, -2500, null, -2500], 'indent' => 1],
                ['label' => 'Profit or loss', 'values' => [null, null, 5300, null, 5300], 'indent' => 1, 'italic' => true],
                ['label' => 'Other comprehensive income', 'values' => [null, null, null, -200, -200], 'indent' => 1, 'italic' => true],
                ['label' => 'TCI for the year', 'values' => [null, null, 5300, -200, 5100], 'underline' => true],
                ['label' => 'Balance at 31 Dec 20X4:', 'values' => [12000, 1300, 10500, 1380, 25180], 'strong' => true],
            ],
        ];

        $pdf = Pdf::loadView('reports.cash_flow', compact('data'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('cash_flow.pdf');
    }






    //function to get the dividends paid to shareholders from the dividends table in the database
    protected function getDividendsPaid()
    {
        $dividends = Dividends::where('status', 'Declared')->get();

        //get the sum of dividends in the column amount
        $dividendsPaid = $dividends->sum('amount');

        return $dividendsPaid;
    }

    //function to get returned earnings
    protected function getRetainedEarnings()
    {
        $dividends = $this->getDividendsPaid();

        //get the net income from the net income service
        $netIncome = app(NetIncome::class)->calculateNetIncome();

        //get the retained earnings by subtracting the dividends paid from the net income
        $retainedEarnings = $netIncome - $dividends;

        return $retainedEarnings;
    }

    //function to 


}