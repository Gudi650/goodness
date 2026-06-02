<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 18mm 14mm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        .sheet {
            width: 100%;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 18px;
        }

        .header .brand,
        .header .meta {
            display: table-cell;
            vertical-align: top;
        }

        .header .meta {
            text-align: right;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 4px 0;
        }

        .muted {
            color: #64748b;
        }

        .panel {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 14px;
        }

        .grid {
            display: table;
            width: 100%;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 10px 0;
        }

        .grid .col {
            display: table-cell;
            vertical-align: top;
            width: 33.3333%;
        }

        .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #64748b;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border-bottom: 1px solid #e2e8f0;
            padding: 8px 6px;
            vertical-align: top;
        }

        th {
            text-align: left;
            font-size: 11px;
            color: #475569;
            background: #f8fafc;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .totals {
            width: 100%;
            margin-top: 10px;
        }

        .totals td {
            border: 0;
            padding: 4px 0;
        }

        .totals .strong {
            font-weight: 700;
            font-size: 13px;
        }

        .notes {
            white-space: pre-line;
            line-height: 1.55;
        }

        .break-avoid {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            <div class="brand">
                <p class="title">INVOICE</p>
                <div class="muted">{{ $invoice->company?->name ?? 'Company' }}</div>
                <div class="muted">Invoice #: {{ $invoice->invoice_number }}</div>
            </div>
            <div class="meta">
                <div><strong>Date:</strong> {{ \Illuminate\Support\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</div>
                <div><strong>Due:</strong> {{ $invoice->due_date ? \Illuminate\Support\Carbon::parse($invoice->due_date)->format('M d, Y') : 'N/A' }}</div>
                <div><strong>Status:</strong> {{ ucfirst($invoice->status) }}</div>
            </div>
        </div>

        <div class="grid panel break-avoid">
            <div class="col">
                <div class="label">Client</div>
                <div><strong>{{ $invoice->client_name }}</strong></div>
                <div class="muted">{{ $invoice->client_email ?: 'No email provided' }}</div>
                <div class="muted">{{ $invoice->client_phone ?: 'No phone provided' }}</div>
                <div class="muted">{{ $invoice->client_address ?: 'No address provided' }}</div>
            </div>
            <div class="col">
                <div class="label">Payment</div>
                <div><strong>{{ ucfirst($invoice->payment_method ?: 'N/A') }}</strong></div>
                <div class="muted">Created by: {{ $invoice->creator?->name ?? 'System' }}</div>
                <div class="muted">Created at: {{ $invoice->created_at?->format('M d, Y h:i A') }}</div>
            </div>
            <div class="col">
                <div class="label">Summary</div>
                <div class="muted">Subtotal: TZS {{ number_format($invoice->subtotal) }}</div>
                <div class="muted">Tax: TZS {{ number_format($invoice->tax_amount) }}</div>
                <div><strong>Total: TZS {{ number_format($invoice->total_amount) }}</strong></div>
            </div>
        </div>

        <div class="panel break-avoid">
            <div class="label">Invoice Items</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%">#</th>
                        <th>Description</th>
                        <th class="right" style="width: 12%">Qty</th>
                        <th class="right" style="width: 18%">Unit Price</th>
                        <th class="right" style="width: 18%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoice->items as $item)
                        <tr class="break-avoid">
                            <td>{{ $item->item_number }}</td>
                            <td>{{ $item->description }}</td>
                            <td class="right">{{ $item->quantity }}</td>
                            <td class="right">TZS {{ number_format($item->unit_price) }}</td>
                            <td class="right">TZS {{ number_format($item->total_price) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="center muted">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($invoice->notes)
            <div class="panel break-avoid">
                <div class="label">Notes</div>
                <div class="notes">{{ $invoice->notes }}</div>
            </div>
        @endif
    </div>
</body>
</html>