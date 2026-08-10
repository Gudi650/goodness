<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $companyName }} - Cash Flow Statement</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #000;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            margin-bottom: 0;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f0f0f0;
        }

        .section {
            font-weight: bold;
            font-style: italic;
            background: #fafafa;
        }

        .subsection {
            font-weight: bold;
            background: #fafafa;
        }

        .desc {
            padding-left: 20px;
        }

        .amount {
            text-align: right;
        }

        .total {
            font-weight: bold;
            background: #f5f5f5;
        }

        .final {
            font-weight: bold;
            background: #f5f5f5;
        }

        .label {
            font-weight: bold;
        }

        .section-label {
            font-weight: bold;
            background: #fafafa;
        }

        .row-divider td {
            border-top: 2px solid #000;
        }

        .double-bottom td {
            border-bottom: 3px double #000;
        }
    </style>
</head>
<body>

    @if(!empty($showActions))
        <div style="text-align:right; margin: 0 0 18px 0;">
            <a href="{{ route('cash-flow-export') }}" style="display:inline-block; padding:10px 14px; background:#111827; color:#fff; text-decoration:none; border-radius:6px; font-size:14px;">
                Generate PDF
            </a>
        </div>
    @endif

    <h1>Cash Flow Statement</h1>
    <div style="padding:16px; border-bottom:1px solid #e2e8f0; display: flex; align-items: middle; flex-direction: row;">
        <table style="border:none; width:100%; margin-bottom:0;">
            <tr>
                <td style="width:50px; border:none; vertical-align:middle;">
                    <img src="{{ public_path('favicon.png') }}" alt="Logo" style="width:40px; height:40px;">
                </td>

                <td style="border:none; vertical-align:middle;">
                    <div style="font-weight:bold; font-size:14px;">
                        Goodness Group
                    </div>
                    <div style="font-size:12px; color:#666;">
                        Enterprise
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <p class="subtitle">For the period ended {{ now()->format('d M Y') }}</p>

    <table>
        <tr>
            <th width="40%" style="text-align:left;">Particulars</th>
            @foreach($years as $year)
                <th width="20%">{{ $year['date_label'] }}</th>
            @endforeach
        </tr>

        <tr class="section">
            <td colspan="{{ count($years) + 1 }}">Cash flows from operating activities</td>
        </tr>

        <tr>
            <td class="desc">Cash, cash equivalents and restricted cash, beginning balances</td>
            @foreach($years as $year)
                <td class="amount">{{ $currencySymbol }}{{ number_format($year['beginning_balance'], 2) }}</td>
            @endforeach
        </tr>

        @foreach($operatingAdjustments as $label => $values)
            <tr>
                <td class="desc">{{ $label }}</td>
                @foreach($values as $value)
                    <td class="amount">{{ $value < 0 ? '(' . $currencySymbol . number_format(abs($value), 2) . ')' : $currencySymbol . number_format($value, 2) }}</td>
                @endforeach
            </tr>
        @endforeach

        @foreach($operatingChanges as $label => $values)
            <tr>
                <td class="desc">{{ $label }}</td>
                @foreach($values as $value)
                    <td class="amount">{{ $value < 0 ? '(' . $currencySymbol . number_format(abs($value), 2) . ')' : $currencySymbol . number_format($value, 2) }}</td>
                @endforeach
            </tr>
        @endforeach

        <tr class="total">
            <td>Net cash from operating activities</td>
            @foreach($years as $year)
                <td class="amount">{{ $currencySymbol }}{{ number_format($year['operating_total'], 2) }}</td>
            @endforeach
        </tr>

        <tr class="section">
            <td colspan="{{ count($years) + 1 }}">Cash flows from investing activities</td>
        </tr>

        @foreach($investingActivities as $label => $values)
            <tr>
                <td class="desc">{{ $label }}</td>
                @foreach($values as $value)
                    <td class="amount">{{ $value < 0 ? '(' . $currencySymbol . number_format(abs($value), 2) . ')' : $currencySymbol . number_format($value, 2) }}</td>
                @endforeach
            </tr>
        @endforeach

        <tr class="total">
            <td>Net cash from investing activities</td>
            @foreach($years as $year)
                <td class="amount">{{ $currencySymbol }}{{ number_format($year['investing_total'], 2) }}</td>
            @endforeach
        </tr>

        <tr class="section">
            <td colspan="{{ count($years) + 1 }}">Cash flows from financing activities</td>
        </tr>

        @foreach($financingActivities as $label => $values)
            <tr>
                <td class="desc">{{ $label }}</td>
                @foreach($values as $value)
                    <td class="amount">{{ $value < 0 ? '(' . $currencySymbol . number_format(abs($value), 2) . ')' : $currencySymbol . number_format($value, 2) }}</td>
                @endforeach
            </tr>
        @endforeach

        <tr class="total">
            <td>Net cash from financing activities</td>
            @foreach($years as $year)
                <td class="amount">{{ $currencySymbol }}{{ number_format($year['financing_total'], 2) }}</td>
            @endforeach
        </tr>

        <tr class="row-divider">
            <td class="label">Net increase/(decrease) in cash and cash equivalents</td>
            @foreach($years as $year)
                <td class="amount label">{{ $currencySymbol }}{{ number_format($year['net_change'], 2) }}</td>
            @endforeach
        </tr>

        <tr class="final double-bottom">
            <td class="label">Cash, cash equivalents and restricted cash, ending balances</td>
            @foreach($years as $year)
                <td class="amount">{{ $currencySymbol }}{{ number_format($year['ending_balance'], 2) }}</td>
            @endforeach
        </tr>

        @if(isset($supplemental))
            <tr class="section">
                <td colspan="{{ count($years) + 1 }}">Supplemental cash flow disclosure</td>
            </tr>
            @foreach($supplemental as $label => $values)
                <tr>
                    <td class="desc">{{ $label }}</td>
                    @foreach($values as $value)
                        <td class="amount">{{ $currencySymbol }}{{ number_format($value, 2) }}</td>
                    @endforeach
                </tr>
            @endforeach
        @endif
    </table>

</body>
</html>