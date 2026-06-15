<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #000;
            font-size: 12px;
        }
        .header-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .subtitle {
            text-align: center;
            font-size: 13px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        th {
            background-color: #f7dcd0; /* Tinted to match image design header */
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row td {
            font-weight: bold;
            border-bottom: 4px double #000; /* Standard accounting double underline */
        }
    </style>
</head>
<body>

    <div class="header-title">Trial Balance</div>
    <div class="subtitle">As at {{ now()->format('d F Y') }}</div>

    <table>
        <thead>
            <tr>
                <th style="width: 55%;">Particulars</th>
                <th style="width: 9%;">L.F.</th>
                <th style="width: 18%;">Dr. Amount</th>
                <th style="width: 18%;">Cr. Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td>{{ $account['name'] }}</td>
                <td class="text-center"></td>
                <td class="text-right">
                    {{ $account['type'] === 'dr' ? number_format($account['amount'], 2) : '' }}
                </td>
                <td class="text-right">
                    {{ $account['type'] === 'cr' ? number_format($account['amount'], 2) : '' }}
                </td>
            </tr>
            @endforeach

            <tr class="total-row">
                <td>Total</td>
                <td></td>
                <td class="text-right">{{ number_format($totalDr, 2) }}</td>
                <td class="text-right">{{ number_format($totalCr, 2) }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>