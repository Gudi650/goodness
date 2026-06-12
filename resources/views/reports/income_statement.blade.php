<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans;
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
    </style>
</head>

<body>

@if(!empty($showActions))
<div style="text-align:right; margin: 0 0 18px 0;">
    <a href="{{ route('income-statement.export') }}" style="display:inline-block; padding:10px 14px; background:#111827; color:#fff; text-decoration:none; border-radius:6px; font-size:14px;">
        Generate PDF
    </a>
</div>
@endif

<h1>Income Statement - {{ $data['period'] }}</h1>
<div style="padding:16px; border-bottom:1px solid #e2e8f0;">
    <table style="border:none; width:100%;">
        <tr>
            <td style="width:50px; border:none; vertical-align:middle;">
                <img src="{{ public_path('favicon.png') }}"
                     alt="Logo"
                     style="width:40px; height:40px;">
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

<p class="subtitle">
    For the period ended {{ now()->format('d M Y') }}
</p>

<table>
    <tr class="section">
        <td colspan="2">Revenue</td>
    </tr>

    @foreach($data['revenue'] as $item)
    <tr>
        <td class="desc">{{ $item['name'] }}</td>
        <td class="amount">${{ number_format($item['amount'], 2) }}</td>
    </tr>
    @endforeach

    <tr class="total">
        <td>Total Revenue</td>
        <td class="amount">${{ number_format($totalRevenue, 2) }}</td>
    </tr>

    <tr class="section">
        <td colspan="2">Cost of Goods Sold (COGS)</td>
    </tr>

    @foreach($data['cogs'] as $item)
    <tr>
        <td class="desc">{{ $item['name'] }}</td>
        <td class="amount">(${{ number_format($item['amount'], 2) }})</td>
    </tr>
    @endforeach

    <tr class="total">
        <td>Total COGS</td>
        <td class="amount">(${{ number_format($totalCogs, 2) }})</td>
    </tr>

    <tr class="section">
        <td colspan="2">Operating Expenses</td>
    </tr>

    @foreach($data['operating_expenses'] as $item)
    <tr>
        <td class="desc">{{ $item['name'] }}</td>
        <td class="amount">(${{ number_format($item['amount'], 2) }})</td>
    </tr>
    @endforeach

    <tr class="total">
        <td>Total Operating Expenses</td>
        <td class="amount">(${{ number_format($totalOperatingExpenses, 2) }})</td>
    </tr>

    <tr class="section">
        <td colspan="2">Operating Income</td>
    </tr>

    <tr>
        <td class="desc">Gross Profit</td>
        <td class="amount">${{ number_format($grossProfit, 2) }}</td>
    </tr>

    <tr>
        <td class="desc">Operating Expenses</td>
        <td class="amount">(${{ number_format($totalOperatingExpenses, 2) }})</td>
    </tr>

    <tr class="total">
        <td>Operating Income</td>
        <td class="amount">${{ number_format($operatingIncome, 2) }}</td>
    </tr>

    <tr class="section">
        <td colspan="2">Other Items</td>
    </tr>

    @foreach($data['other_items'] as $item)
    <tr>
        <td class="desc">{{ $item['name'] }}</td>

        <td class="amount">
            @if($item['amount'] < 0)
                (${{ number_format(abs($item['amount']), 2) }})
            @else
                ${{ number_format($item['amount'], 2) }}
            @endif
        </td>
    </tr>
    @endforeach

    <tr class="total">
        <td>Pre-Tax Income</td>
        <td class="amount">${{ number_format($preTaxIncome, 2) }}</td>
    </tr>

    <tr class="total">
        <td>Income Tax Expense</td>
        <td class="amount">(${{ number_format($data['tax_expense'], 2) }})</td>
    </tr>

    <tr class="final">
        <td>Net Income</td>
        <td class="amount">${{ number_format($netIncome, 2) }}</td>
    </tr>
</table>

</body>
</html>