<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expense Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #000; }
        h2 { text-align: center; margin-bottom: 0; }
        .subtitle { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f0f0f0; }
        .section { font-weight: bold; font-style: italic; background: #fafafa; }
        .amount { text-align: right; }
        .total { font-weight: bold; background: #f5f5f5; }
        .no-border td { border: none; }
    </style>
</head>
<body>

@if (!empty($showActions))
    <div style="text-align:right; margin: 0 0 18px 0;">
        <a href="{{ route('expense-report-export', request()->query()) }}"
           style="display:inline-block; padding:10px 14px; background:#111827; color:#fff; text-decoration:none; border-radius:6px; font-size:14px;">
            Generate PDF
        </a>
    </div>
@endif

<h2>Expense Report</h2>

<div style="padding:16px; border-bottom:1px solid #e2e8f0;">
    <table class="no-border" style="margin-bottom:0;">
        <tr>
            <td style="width:50px; vertical-align:middle;">
                @if (!empty($showActions))
                    <img src="{{ asset('favicon.png') }}" alt="Logo" style="width:40px; height:40px;">
                @elseif (function_exists('imagecreatefrompng') && is_file(public_path('favicon.png')))
                    <img src="{{ public_path('favicon.png') }}" alt="Logo" style="width:40px; height:40px;">
                @endif
            </td>
            <td style="vertical-align:middle;">
                <div style="font-weight:bold; font-size:14px;">
                    {{ $reportCompanyName ?? 'All Companies' }}
                </div>
                <div style="font-size:12px; color:#666;">Enterprise</div>
            </td>
        </tr>
    </table>
</div>

<p class="subtitle">For the period {{ $periodLabel ?? now()->format('d M Y') }}</p>

<table>
    <tr class="section">
        <td colspan="8">Expenses</td>
    </tr>
    <tr>
        <th>Expense No</th>
        <th>Date</th>
        <th>Company</th>
        <th>Department</th>
        <th>Category</th>
        <th>Status</th>
        <th class="amount">Gross</th>
        <th class="amount">Net</th>
    </tr>
    @forelse ($expenses as $expense)
        <tr>
            <td>{{ $expense->expense_number ?? 'N/A' }}</td>
            <td>{{ optional($expense->expense_date)->format('d M Y') ?? 'N/A' }}</td>
            <td>{{ $expense->company?->name ?? 'N/A' }}</td>
            <td>{{ $expense->department?->name ?? 'N/A' }}</td>
            <td>{{ $expense->category ?? 'N/A' }}</td>
            <td>{{ $expense->status ?? 'N/A' }}</td>
            <td class="amount">Tsh{{ number_format((float) $expense->amount, 2) }}</td>
            <td class="amount">Tsh{{ number_format((float) $expense->net_amount, 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8">No expenses found for the selected filters.</td>
        </tr>
    @endforelse
    <tr class="total">
        <td colspan="6">Total ({{ $totals['count'] ?? 0 }})</td>
        <td class="amount">Tsh{{ number_format((float) ($totals['gross'] ?? 0), 2) }}</td>
        <td class="amount">Tsh{{ number_format((float) ($totals['net'] ?? 0), 2) }}</td>
    </tr>
</table>

</body>
</html>
