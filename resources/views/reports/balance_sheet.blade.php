<!DOCTYPE html>
<html>
<head>
    <style>

        body{
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        h2{
            text-align:center;
            margin-bottom:0;
        }

        .subtitle{
            text-align:center;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th, td{
            border:1px solid #000;
            padding:6px;
        }

        th{
            background:#f0f0f0;
        }

        .section{
            font-weight:bold;
            font-style:italic;
            background:#fafafa;
        }

        .subcategory{
            font-weight:bold;
        }

        .amount{
            text-align:right;
        }

        .total{
            font-weight:bold;
            background:#f5f5f5;
        }

    </style>
</head>

<body>

@if(!empty($showActions))
<div style="text-align:right; margin: 0 0 18px 0;">
    <a href="{{ route('balance_sheet') }}" style="display:inline-block; padding:10px 14px; background:#111827; color:#fff; text-decoration:none; border-radius:6px; font-size:14px;">
        Generate PDF
    </a>
</div>

@endif

<h2>Balance Sheet</h2>
<div style="padding:16px; border-bottom:1px solid #e2e8f0; display: flex; align-items: middle; flex-direction: row;">
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
    As at {{ now()->format('d M Y') }}
</p>

<table>

    <tr>
        <th width="70%">Particulars</th>
        <th width="30%">Amount</th>
    </tr>

    <tr class="section">
        <td colspan="2">Assets</td>
    </tr>

    <tr class="subcategory">
        <td colspan="2">Non-Current Assets</td>
    </tr>

    {{-- loop through non current assets --}}
    @foreach($nonCurrentAssets as $item)
    <tr>
        <td>{{ $item['name'] }}</td>
        <td class="amount">
            {{ number_format(collect($item)->sum('amount'), 2) }}
        </td>
    </tr>
    @endforeach

    <tr class="subcategory">
        <td colspan="2">Current Assets</td>
    </tr>

    @foreach($currentAssets as $item)
    <tr>
        <td>{{ $item['name'] }}</td>
        <td class="amount">
            {{ number_format(collect($item)->sum('amount'), 2) }}
        </td>
    </tr>
    @endforeach

    <tr class="total">
        <td>Total Assets</td>
        <td class="amount">
            {{ number_format($totalAssets, 2) }}
        </td>
    </tr>

    <tr class="section">
        <td colspan="2">Equity and Liabilities</td>
    </tr>

    <tr class="subcategory">
        <td colspan="2">Equity</td>
    </tr>

    @foreach($equityLiabilities['equity'] as $item)
    <tr>
        <td>{{ $item['name'] }}</td>
        <td class="amount">
            {{ number_format(collect($item)->sum('amount'), 2) }}
        </td>
    </tr>
    @endforeach

    <tr class="subcategory">
        <td colspan="2">Non-Current Liabilities</td>
    </tr>

    @foreach($nonCurrentLiabilities as $item)
    <tr>
        <td>{{ $item['name'] }}</td>
        <td class="amount">
            {{ number_format(collect($item)->sum('amount'), 2) }}
        </td>
    </tr>
    @endforeach

    <tr class="subcategory">
        <td colspan="2">Current Liabilities</td>
    </tr>

    @foreach($currentLiabilities as $item)
    <tr>
        <td>{{ $item['name'] }}</td>
        <td class="amount">
            {{ number_format(collect($item)->sum('amount'), 2) }}
        </td>
    </tr>
    @endforeach

    <tr class="total">
        <td>Total Equity and Liabilities</td>
        <td class="amount">
            {{ number_format($totalEquityAndLiabilities,2) }}
        </td>
    </tr>

</table>

</body>
</html>