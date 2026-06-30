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
            'company' => 'Apple Inc.',
            'title' => 'CONSOLIDATED STATEMENTS OF CASH FLOWS',
            'scale' => '(In millions)',
            'years' => ['2022', '2021', '2020'],
            'dates' => ['September 24, 2022', 'September 25, 2021', 'September 26, 2020'],
            
            'beginning_cash' => [35929, 39789, 50224],
            
            'operating_net_income' => [99803, 94680, 57411],
            'operating_adjustments' => [
                'Depreciation and amortization' => [11104, 11284, 11056],
                'Share-based compensation expense' => [9038, 7906, 6829],
                'Deferred income tax expense/(benefit)' => [895, -4774, -215],
                'Other' => [111, -147, -97],
            ],
            'operating_changes' => [
                'Accounts receivable, net' => [-1823, -10125, 6917],
                'Inventories' => [1484, -2642, -127],
                'Vendor non-trade receivables' => [-7520, -3903, 1553],
                'Other current and non-current assets' => [-6499, -8042, -9588],
                'Accounts payable' => [9448, 12326, -4062],
                'Deferred revenue' => [478, 1676, 2081],
                'Other current and non-current liabilities' => [5632, 5799, 8916],
            ],
            'operating_total' => [122151, 104038, 80674],

            'investing_activities' => [
                'Purchases of marketable securities' => [-76923, -109558, -114938],
                'Proceeds from maturities of marketable securities' => [29917, 59023, 69918],
                'Proceeds from sales of marketable securities' => [37446, 47460, 50473],
                'Payments for acquisition of property, plant and equipment' => [-10708, -11085, -7309],
                'Payments made in connection with business acquisitions, net' => [-306, -33, -1524],
                'Other' => [-1780, -352, -909],
            ],
            'investing_total' => [-22354, -14545, -4289],

            'financing_activities' => [
                'Payments for taxes related to net share settlement of equity awards' => [-6223, -6556, -3634],
                'Payments for dividends and dividend equivalents' => [-14841, -14467, -14081],
                'Repurchases of common stock' => [-89402, -85971, -72358],
                'Proceeds from issuance of term debt, net' => [5465, 20393, 16091],
                'Repayments of term debt' => [-9543, -8750, -12629],
                'Proceeds from/(Repayments of) commercial paper, net' => [3955, 1022, -963],
                'Other' => [-160, 976, 754],
            ],
            'financing_total' => [-110749, -93353, -86820],

            'net_decrease' => [-10952, -3860, -10435],
            'ending_cash' => [24977, 35929, 39789],
            
            'supplemental' => [
                'Cash paid for income taxes, net' => [19573, 25385, 9501],
                'Cash paid for interest' => [2865, 2687, 3002]
            ]
        ];

        $pdf = Pdf::loadView('reports.cash_flow', compact('data'));
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