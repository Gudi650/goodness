<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expense Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2 {
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

        .label {
            width: 22%;
            font-weight: bold;
            background: #fafafa;
        }

        .amount {
            text-align: right;
        }

        .total {
            font-weight: bold;
            background: #f5f5f5;
        }

        .no-border td {
            border: none;
        }
    </style>
</head>
<body>

@if (!empty($showActions))
    <div style="text-align:right; margin: 0 0 18px 0;">
        <a href="{{ route('expense-report', ['expense_id' => $expense->id, 'download' => 1]) }}"
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
                <img src="{{ public_path('favicon.png') }}" alt="Logo" style="width:40px; height:40px;">
            </td>
            <td style="vertical-align:middle;">
                <div style="font-weight:bold; font-size:14px;">
                    {{ $expense->company?->name ?? 'Goodness Group' }}
                </div>
                <div style="font-size:12px; color:#666;">
                    Enterprise
                </div>
            </td>
            <td style="text-align:right; vertical-align:middle;">
                <div style="font-weight:bold;">{{ $expense->expense_number ?? 'N/A' }}</div>
                <div style="font-size:11px; color:#666;">{{ strtoupper($expense->status ?? 'N/A') }}</div>
            </td>
        </tr>
    </table>
</div>

<p class="subtitle">
    As at {{ optional($expense->expense_date)->format('d M Y') ?? now()->format('d M Y') }}
</p>

<table>
    <tr class="section">
        <td colspan="4">Expense Information</td>
    </tr>
    <tr>
        <td class="label">Expense Number</td>
        <td>{{ $expense->expense_number ?? 'N/A' }}</td>
        <td class="label">Expense Date</td>
        <td>{{ optional($expense->expense_date)->format('d M Y') ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label">Department</td>
        <td>{{ $expense->department?->name ?? 'N/A' }}</td>
        <td class="label">Category</td>
        <td>{{ $expense->category ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label">Sub Category</td>
        <td>{{ $expense->sub_category ?? ($expense->financeItem?->item_name ?? 'N/A') }}</td>
        <td class="label">Payment Method</td>
        <td>{{ $expense->payment_method ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label">Reference</td>
        <td>{{ $expense->reference_number ?? 'N/A' }}</td>
        <td class="label">Bank Account</td>
        <td>{{ $expense->bankAccount?->account_name ?? 'N/A' }}</td>
    </tr>
</table>

<table>
    <tr class="section">
        <td colspan="2">Description</td>
    </tr>
    <tr>
        <td colspan="2">{{ $expense->description ?: 'No description provided.' }}</td>
    </tr>
</table>

<table>
    <tr class="section">
        <td colspan="2">Financial Details</td>
    </tr>
    <tr>
        <th>Description</th>
        <th class="amount" style="width:30%;">Amount</th>
    </tr>
    <tr>
        <td>Net Amount</td>
        <td class="amount">Tsh{{ number_format((float) $expense->net_amount, 2) }}</td>
    </tr>
    @if($expense->vat_included || (float) $expense->vat_amount > 0)
        <tr>
            <td>
                VAT
                @if($expense->vat_rate !== null)
                    ({{ number_format((float) $expense->vat_rate, 2) }}%)
                @endif
            </td>
            <td class="amount">Tsh{{ number_format((float) $expense->vat_amount, 2) }}</td>
        </tr>
    @endif
    <tr class="total">
        <td>Total Expense</td>
        <td class="amount">Tsh{{ number_format((float) $expense->amount, 2) }}</td>
    </tr>
</table>

@if(!empty($expense->notes))
<table>
    <tr class="section">
        <td>Notes</td>
    </tr>
    <tr>
        <td>{{ $expense->notes }}</td>
    </tr>
</table>
@endif

<table>
    <tr class="section">
        <td colspan="3">Authorization &amp; Workflow</td>
    </tr>
    <tr>
        <th>Action</th>
        <th>Person</th>
        <th>Date</th>
    </tr>
    <tr>
        <td>Prepared By</td>
        <td>{{ $expense->creator?->name ?? 'N/A' }}</td>
        <td>{{ optional($expense->submitted_at)->format('d M Y H:i') ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td>Checked By</td>
        <td>{{ $expense->checker?->name ?? 'N/A' }}</td>
        <td>{{ optional($expense->reviewed_at)->format('d M Y H:i') ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td>Approved By</td>
        <td>{{ $expense->approver?->name ?? 'N/A' }}</td>
        <td>{{ optional($expense->approved_at)->format('d M Y H:i') ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td>Issued By</td>
        <td>{{ $expense->issuer?->name ?? 'N/A' }}</td>
        <td>N/A</td>
    </tr>
</table>

</body>
</html>
