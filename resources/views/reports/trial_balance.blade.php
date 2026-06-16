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


            {{-- loop through non current assets --}}
            @foreach ($nonCurrentAssets as $type => $items)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                    <td class="text-center"></td>

                    {{-- Debit column --}}
                    @php $debitItems = $items->where('type', 'dr'); @endphp
                    <td class="text-right">
                        {{ $debitItems->isNotEmpty() 
                            ? number_format($debitItems->sum('amount'), 2) 
                            : '-' }}
                    </td>

                    {{-- Credit column --}}
                    @php $creditItems = $items->where('type', 'cr'); @endphp
                    <td class="text-right">
                        {{ $creditItems->isNotEmpty() 
                            ? number_format($creditItems->sum('amount'), 2) 
                            : '-' }}
                    </td>
                </tr>
            @endforeach



            {{-- loop through current assets --}}
            @foreach ($currentAssets as $type => $items)

                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                    <td class="text-center"></td>

                    {{-- Debit column --}}
                    @php $debitItems = $items->where('type', 'dr'); @endphp
                    <td class="text-right">
                        {{ $debitItems->isNotEmpty() 
                            ? number_format($debitItems->sum('amount'), 2) 
                            : '-' }}
                    </td>

                    {{-- Credit column --}}
                    @php $creditItems = $items->where('type', 'cr'); @endphp
                    <td class="text-right">
                        {{ $creditItems->isNotEmpty() 
                            ? number_format($creditItems->sum('amount'), 2) 
                            : '-' }}
                    </td>
                </tr>

            @endforeach

            {{-- loop through current liabilities --}}
            @foreach ($currentLiabilities as $type => $items)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                    <td class="text-center"></td>

                    {{-- Debit column --}}
                    @php $debitItems = $items->where('type', 'dr'); @endphp
                    <td class="text-right">
                        {{ $debitItems->isNotEmpty() 
                            ? number_format($debitItems->sum('amount'), 2) 
                            : '-' }}
                    </td>

                    {{-- Credit column --}}
                    @php $creditItems = $items->where('type', 'cr'); @endphp
                    <td class="text-right">
                        {{ $creditItems->isNotEmpty() 
                            ? number_format($creditItems->sum('amount'), 2) 
                            : '-' }}
                    </td>
                </tr>
            @endforeach


            {{-- loop through non current liabilities --}}
            @foreach ($nonCurrentLiabilities as $type => $items)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                    <td class="text-center"></td>

                    {{-- Debit column --}}
                    @php $debitItems = $items->where('type', 'dr'); @endphp
                    <td class="text-right">
                        {{ $debitItems->isNotEmpty() 
                            ? number_format($debitItems->sum('amount'), 2) 
                            : '-' }}
                    </td>

                    {{-- Credit column --}}
                    @php $creditItems = $items->where('type', 'cr'); @endphp
                    <td class="text-right">
                        {{ $creditItems->isNotEmpty() 
                            ? number_format($creditItems->sum('amount'), 2) 
                            : '-' }}
                    </td>
                </tr>
            @endforeach

            {{-- loop through the costs of goods sold --}}
            @foreach ($costOfGoodsSold as $type => $items)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                    <td class="text-center"></td>

                    {{-- Debit column --}}
                    @php $debitItems = $items->where('type', 'dr'); @endphp
                    <td class="text-right">
                        {{ $debitItems->isNotEmpty() 
                            ? number_format($debitItems->sum('amount'), 2) 
                            : '-' }}
                    </td>

                    {{-- Credit column --}}
                    @php $creditItems = $items->where('type', 'cr'); @endphp
                    <td class="text-right">
                        {{ $creditItems->isNotEmpty() 
                            ? number_format($creditItems->sum('amount'), 2) 
                            : '-' }}
                    </td>
                </tr>
            @endforeach

            {{-- loop through the revenues --}}
            @foreach ($revenues as $type => $items)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                    <td class="text-center"></td>
                    {{-- Debit column --}}
                    @php $debitItems = $items->where('type', 'dr'); @endphp
                    <td class="text-right">
                        {{ $debitItems->isNotEmpty() 
                            ? number_format($debitItems->sum('amount'), 2) 
                            : '-' }}
                    </td>
                    {{-- Credit column --}}
                    @php $creditItems = $items->where('type', 'cr'); @endphp
                    <td class="text-right">
                        {{ $creditItems->isNotEmpty() 
                            ? number_format($creditItems->sum('amount'), 2) 
                            : '-' }}
                    </td>
                </tr>
            @endforeach

            {{-- loop through operating expenses --}}
            @foreach ($operationalCosts as $type => $items)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                    <td class="text-center"></td>
                    {{-- Debit column --}}
                    @php $debitItems = $items->where('type', 'dr'); @endphp
                    <td class="text-right">
                        {{ $debitItems->isNotEmpty() 
                            ? number_format($debitItems->sum('amount'), 2) 
                            : '-' }}
                    </td>
                    {{-- Credit column --}}
                    @php $creditItems = $items->where('type', 'cr'); @endphp
                    <td class="text-right">
                        {{ $creditItems->isNotEmpty() 
                            ? number_format($creditItems->sum('amount'), 2) 
                            : '-' }}
                    </td>
                </tr>
            @endforeach

            {{-- loop through operational costs --}}


            {{-- loop through the


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
            --}}

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