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
    <a href="{{ route('income-statement-export') }}" style="display:inline-block; padding:10px 14px; background:#111827; color:#fff; text-decoration:none; border-radius:6px; font-size:14px;">
        Generate PDF
    </a>
</div>
@endif

<!-- <h1>Income Statement - {{ $data['period'] }} </h1> -->
<h1>Income Statement</h1>
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
    For the period ended {{ now()->format('d M Y') }}
</p>

<table>
    <tr class="section">
        <td colspan="2">Revenue</td>
    </tr>

    @foreach($totalRevenuesByCategory as $category => $amount)
    <tr>
        <td class="desc">{{ $category }}</td>
        <td class="amount">Tsh{{ number_format($amount, 2) }}</td>
    </tr>
    @endforeach

    <tr class="total">
        <td>Total Revenue</td>
        <td class="amount">Tsh{{ number_format($totalRevenue, 2) }}</td>
    </tr>

    @php
        $cogsCategory = $totalExpensesByCategory->keys()->first(function ($key) {
            $k = strtolower((string) $key);
            return str_contains($k, 'cogs') || str_contains($k, 'cost of good sold') || str_contains($k, 'cost of goods sold');
        });
        $cogsItems = $cogsCategory ? $totalExpensesByCategory->get($cogsCategory, collect()) : collect();
        $cogsTotal = $cogsItems->sum();
    @endphp

    <tr class="section">
        <td colspan="2">Cost of Goods Sold (COGS)</td>
    </tr>

    @foreach($cogsItems as $itemName => $amount)
    <tr>
        <td class="desc">{{ $itemName }}</td>
        <td class="amount">Tsh{{ number_format($amount, 2) }}</td>
    </tr>
    @endforeach

    <tr class="total">
        <td>Total Cost of Goods Sold (COGS)</td>
        <td class="amount">Tsh{{ number_format($cogsTotal, 2) }}</td>
    </tr>

    <tr class="total">
        <td>Gross Profit</td>
        <td class="amount">Tsh{{ number_format($grossProfit, 2) }}</td>
    </tr>

    @php
        $otherExpensesTotal = 0;
    @endphp

    <tr class="section">
        <td colspan="2">Expenses</td>
    </tr>

    @foreach($totalExpensesByCategory as $category => $items)
        @php
            $isInvestment = str_contains(strtolower((string) $category), 'investment');
        @endphp
        @if($category !== $cogsCategory && ! $isInvestment)
            <tr class="section">
                <td colspan="2">{{ $category }}</td>
            </tr>

            @php
                $categoryTotal = 0;
            @endphp

            @foreach($items as $itemName => $amount)
                @php
                    $categoryTotal += $amount;
                    $otherExpensesTotal += $amount;
                @endphp

                <tr>
                    <td class="desc">{{ $itemName }}</td>
                    <td class="amount">Tsh{{ number_format($amount, 2) }}</td>
                </tr>
            @endforeach

            <tr class="total">
                <td>Total {{ $category }}</td>
                <td class="amount">Tsh{{ number_format($categoryTotal, 2) }}</td>
            </tr>
        @endif
    @endforeach

    <tr class="total">
        <td>Total Expenses</td>
        <td class="amount">Tsh{{ number_format($otherExpensesTotal, 2) }}</td>
    </tr>

    <tr class="total">
        <td>Operating Income</td>
        <td class="amount">Tsh{{ number_format($operatingIncome, 2) }}</td>
    </tr>

    <tr>
        <td class="desc">Gain/(Loss) on asset disposal</td>
        <td class="amount">Tsh{{ number_format($otherItemsTotal ?? 0, 2) }}</td>
    </tr>

    <tr class="total">
        <td>Pre-Tax Income</td>
        <td class="amount">Tsh {{ number_format($preTaxIncome, 2) }}</td>
    </tr>

    <tr class="total">
        <td>Income Tax Expense</td>
        <td class="amount">Tsh({{ number_format($taxExpense, 2) }})</td>
    </tr>

    <tr class="final">
        <td>Net Income</td>
        <td class="amount">Tsh{{ number_format($netIncome, 2) }}</td>
    </tr>
</table>

</body>
</html>