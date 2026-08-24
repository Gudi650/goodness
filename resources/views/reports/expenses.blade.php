<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Expense Report </title>

    <style>
        @page {
            margin: 30px 35px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
        }

        /* =========================
           HEADER
        ========================== */

        .header {
            width: 100%;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 15px;
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-left {
            width: 65%;
            vertical-align: top;
        }

        .header-right {
            width: 35%;
            text-align: right;
            vertical-align: top;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 9px;
            color: #6b7280;
            line-height: 1.5;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 6px;
        }

        .report-number {
            font-size: 10px;
            color: #6b7280;
        }

        /* =========================
           STATUS
        ========================== */

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 7px;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-default {
            background: #e5e7eb;
            color: #374151;
        }

        /* =========================
           SECTION
        ========================== */

        .section {
            margin-top: 18px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #111827;
            background: #f3f4f6;
            border-left: 4px solid #374151;
            padding: 8px 10px;
            margin-bottom: 0;
        }

        /* =========================
           INFORMATION TABLE
        ========================== */

        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
        }

        .info-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .label {
            width: 20%;
            color: #6b7280;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .value {
            width: 30%;
            color: #111827;
        }

        /* =========================
           DESCRIPTION
        ========================== */

        .description-box {
            border: 1px solid #e5e7eb;
            padding: 12px;
            min-height: 70px;
            line-height: 1.6;
        }

        .notes-box {
            border: 1px solid #e5e7eb;
            padding: 12px;
            min-height: 50px;
            line-height: 1.6;
        }

        .empty-text {
            color: #9ca3af;
            font-style: italic;
        }

        /* =========================
           FINANCIAL TABLE
        ========================== */

        .financial-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
        }

        .financial-table th {
            background: #f3f4f6;
            color: #374151;
            text-align: left;
            padding: 9px 10px;
            font-size: 9px;
            text-transform: uppercase;
        }

        .financial-table td {
            padding: 9px 10px;
            border-top: 1px solid #e5e7eb;
        }

        .amount {
            text-align: right;
            font-family: DejaVu Sans, sans-serif;
        }

        .total-row td {
            font-weight: bold;
            font-size: 12px;
            border-top: 2px solid #1f2937;
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .total-label {
            text-align: right;
        }

        /* =========================
           WORKFLOW
        ========================== */

        .workflow-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
        }

        .workflow-table th {
            background: #f3f4f6;
            text-align: left;
            padding: 8px;
            font-size: 9px;
            text-transform: uppercase;
        }

        .workflow-table td {
            padding: 9px 8px;
            border-top: 1px solid #e5e7eb;
        }

        /* =========================
           SIGNATURES
        ========================== */

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        .signature-cell {
            width: 33.33%;
            padding-right: 20px;
            vertical-align: bottom;
        }

        .signature-line {
            border-bottom: 1px solid #374151;
            height: 35px;
            margin-bottom: 6px;
        }

        .signature-name {
            font-size: 9px;
            font-weight: bold;
        }

        .signature-label {
            font-size: 8px;
            color: #6b7280;
            margin-top: 3px;
        }

        /* =========================
           FOOTER
        ========================== */

        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
        }

        .page-number {
            text-align: right;
            font-size: 8px;
            color: #9ca3af;
        }
    </style>
</head>

<body>

<div class="page">

    {{-- ============================================================
         HEADER
    ============================================================= --}}

    <div class="header">

        <table class="header-table">
            <tr>

                <td class="header-left">

                    {{-- Replace with your actual company logo if needed 
                    @if(isset($expense->company->logo))
                        <img
                            src="{{ public_path('storage/' . $expense->company->logo) }}"
                            style="max-width: 120px; max-height: 55px; margin-bottom: 8px;"
                        >
                    @endif
                    --}}

                    <div class="company-name">
                        {{ $expense->company?->name ?? 'Company Name' }}
                    </div>

                    <div class="company-details">

                        @if(!empty($expense->company?->address))
                            {{ $expense->company->address }}<br>
                        @endif

                        @if(!empty($expense->company?->phone))
                            Tel: {{ $expense->company->phone }}
                        @endif

                        @if(!empty($expense->company?->email))
                            &nbsp; | &nbsp; {{ $expense->company->email }}
                        @endif

                    </div>

                </td>

                <td class="header-right">

                    <div class="report-title">
                        EXPENSE REPORT
                    </div>

                    <div class="report-number">
                        {{ $expense->expense_number }}
                    </div>

                    @php
                        $statusClass = match(strtolower($expense->status ?? '')) {
                            'approved' => 'status-approved',
                            'pending' => 'status-pending',
                            'rejected' => 'status-rejected',
                            default => 'status-default',
                        };
                    @endphp

                    <span class="status {{ $statusClass }}">
                        {{ $expense->status ?? 'N/A' }}
                    </span>

                </td>

            </tr>
        </table>

    </div>


    {{-- ============================================================
         EXPENSE INFORMATION
    ============================================================= --}}

    <div class="section">

        <div class="section-title">
            Expense Information
        </div>

        <table class="info-table">

            <tr>

                <td class="label">
                    Expense Number
                </td>

                <td class="value">
                    {{ $expense->expense_number ?? 'N/A' }}
                </td>

                <td class="label">
                    Expense Date
                </td>

                <td class="value">
                    {{ $expense->expense_date?->format('d M Y') ?? 'N/A' }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Department
                </td>

                <td class="value">
                    {{ $expense->department?->name ?? 'N/A' }}
                </td>

                <td class="label">
                    Category
                </td>

                <td class="value">
                    {{ $expense->category ?? 'N/A' }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Sub Category
                </td>

                <td class="value">
                    {{ $expense->sub_category ?? ($expense->financeItem?->item_name ?? 'N/A') }}
                </td>

                <td class="label">
                    Payment Method
                </td>

                <td class="value">
                    {{ $expense->payment_method ?? 'N/A' }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Reference Number
                </td>

                <td class="value">
                    {{ $expense->reference_number ?? 'N/A' }}
                </td>

                <td class="label">
                    Bank Account
                </td>

                <td class="value">
                    {{ $expense->bankAccount?->account_name ?? 'N/A' }}
                </td>

            </tr>

        </table>

    </div>


    {{-- ============================================================
         DESCRIPTION
    ============================================================= --}}

    <div class="section">

        <div class="section-title">
            Description
        </div>

        <div class="description-box">

            @if(!empty($expense->description))

                {{ $expense->description }}

            @else

                <span class="empty-text">
                    No description provided.
                </span>

            @endif

        </div>

    </div>


    {{-- ============================================================
         FINANCIAL DETAILS
    ============================================================= --}}

    <div class="section">

        <div class="section-title">
            Financial Details
        </div>

        <table class="financial-table">

            <thead>
                <tr>

                    <th>
                        Description
                    </th>

                    <th style="width: 25%; text-align: right;">
                        Amount
                    </th>

                </tr>
            </thead>

            <tbody>

                <tr>

                    <td>
                        Net Amount
                    </td>

                    <td class="amount">
                        {{ number_format((float) $expense->net_amount, 2) }}
                    </td>

                </tr>

                @if($expense->vat_included || (float) $expense->vat_amount > 0)

                    <tr>

                        <td>
                            VAT
                            @if($expense->vat_rate !== null)
                                ({{ number_format((float) $expense->vat_rate, 2) }}%)
                            @endif
                        </td>

                        <td class="amount">
                            {{ number_format((float) $expense->vat_amount, 2) }}
                        </td>

                    </tr>

                @endif

                <tr class="total-row">

                    <td class="total-label">
                        TOTAL EXPENSE
                    </td>

                    <td class="amount">
                        {{ number_format((float) $expense->amount, 2) }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    {{-- ============================================================
         NOTES
    ============================================================= --}}

    @if(!empty($expense->notes))

        <div class="section">

            <div class="section-title">
                Notes
            </div>

            <div class="notes-box">
                {{ $expense->notes }}
            </div>

        </div>

    @endif


    {{-- ============================================================
         WORKFLOW / AUTHORIZATION
    ============================================================= --}}

    <div class="section">

        <div class="section-title">
            Authorization & Workflow
        </div>

        <table class="workflow-table">

            <thead>

                <tr>

                    <th>
                        Action
                    </th>

                    <th>
                        Person
                    </th>

                    <th>
                        Date
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>
                        Prepared By
                    </td>

                    <td>
                        {{ $expense->creator?->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $expense->submitted_at?->format('d M Y H:i') ?? 'N/A' }}
                    </td>

                </tr>

                <tr>

                    <td>
                        Checked By
                    </td>

                    <td>
                        {{ $expense->checker?->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $expense->reviewed_at?->format('d M Y H:i') ?? 'N/A' }}
                    </td>

                </tr>

                <tr>

                    <td>
                        Approved By
                    </td>

                    <td>
                        {{ $expense->approver?->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $expense->approved_at?->format('d M Y H:i') ?? 'N/A' }}
                    </td>

                </tr>

                <tr>

                    <td>
                        Issued By
                    </td>

                    <td>
                        {{ $expense->issuer?->name ?? 'N/A' }}
                    </td>

                    <td>
                        N/A
                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    {{-- ============================================================
         SIGNATURES
    ============================================================= --}}

    <table class="signature-table">

        <tr>

            <td class="signature-cell">

                <div class="signature-line"></div>

                <div class="signature-name">
                    {{ $expense->creator?->name ?? 'Prepared By' }}
                </div>

                <div class="signature-label">
                    Prepared By
                </div>

            </td>


            <td class="signature-cell">

                <div class="signature-line"></div>

                <div class="signature-name">
                    {{ $expense->checker?->name ?? 'Checked By' }}
                </div>

                <div class="signature-label">
                    Checked By
                </div>

            </td>


            <td class="signature-cell">

                <div class="signature-line"></div>

                <div class="signature-name">
                    {{ $expense->approver?->name ?? 'Approved By' }}
                </div>

                <div class="signature-label">
                    Approved By
                </div>

            </td>

        </tr>

    </table>


    {{-- ============================================================
         FOOTER
    ============================================================= --}}

    <div class="footer">

        This document is an electronically generated expense report.

        @if(!empty($expense->expense_number))
            &nbsp; | &nbsp; Expense No: {{ $expense->expense_number }}
        @endif

    </div>

</div>

</body>
</html>
