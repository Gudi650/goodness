<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 16px 18px;
        }

        body {
            font-family: Arial, DejaVu Sans, sans-serif;
            color: #000;
            font-size: 11px;
            line-height: 1.15;
        }

        .report-header {
            margin-bottom: 12px;
        }

        .company {
            font-size: 14px;
            font-weight: 700;
            text-decoration: underline;
            margin-bottom: 1px;
        }

        .title {
            font-size: 13px;
            font-weight: 700;
            text-decoration: underline;
            margin-bottom: 1px;
        }

        .period {
            font-size: 12px;
            font-weight: 700;
            text-decoration: underline;
            margin-bottom: 1px;
        }

        .scale {
            font-size: 12px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead th {
            font-style: italic;
            font-weight: 700;
            text-align: center;
            padding: 4px 4px 6px;
            border-bottom: 1px solid #000;
        }

        tbody td {
            padding: 2px 4px;
            vertical-align: bottom;
        }

        .label {
            text-align: left;
            width: 34%;
            white-space: nowrap;
        }

        .indent-1 {
            padding-left: 14px;
        }

        .italic {
            font-style: italic;
        }

        .amount {
            text-align: right;
            width: 13.2%;
            white-space: nowrap;
        }

        .section-row td {
            padding-top: 8px;
            font-weight: 700;
        }

        .strong-row td {
            font-weight: 700;
        }

        .underline-row td {
            border-bottom: 1px solid #000;
        }

        .top-rule td {
            border-top: 1px solid #000;
        }
    </style>
</head>
<body>

<div class="report-header">
    <div class="company">{{ $data['company'] }} - {{ $data['title'] }}</div>
    <div class="period">{{ $data['period'] }}</div>
    <div class="scale">{{ $data['scale'] }}</div>
</div>

<table>
    <thead>
        <tr>
            <th style="text-align:left;"></th>
            @foreach($data['columns'] as $column)
                <th>{{ $column }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data['rows'] as $row)
            @if(!empty($row['section']))
                <tr class="section-row">
                    <td colspan="6">{{ $row['label'] }}</td>
                </tr>
            @else
                @php
                    $rowClasses = [];
                    if (!empty($row['strong'])) {
                        $rowClasses[] = 'strong-row';
                    }
                    if (!empty($row['underline'])) {
                        $rowClasses[] = 'underline-row';
                    }
                @endphp
                <tr class="{{ implode(' ', $rowClasses) }}">
                    <td class="label {{ !empty($row['indent']) ? 'indent-1' : '' }} {{ !empty($row['italic']) ? 'italic' : '' }}">{{ $row['label'] }}</td>
                    @foreach($row['values'] as $value)
                        <td class="amount">
                            @if(is_null($value))
                                &nbsp;
                            @elseif($value < 0)
                                ({{ number_format(abs($value)) }})
                            @else
                                {{ number_format($value) }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

</body>
</html>