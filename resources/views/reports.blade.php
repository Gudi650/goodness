<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Reports - Goodness Group</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors: { brand: {50:'#fff8e5',100:'#fde6a1',500:'#f0b73a',600:'#eaa106',700:'#c88600'} }, fontFamily:{ sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] } } } }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:Inter,sans-serif}h1,h2,nav,button{font-family:Outfit,sans-serif}</style>
</head>
<body class="bg-slate-50 text-slate-800">
  @include('components.topbar')
  @include('components.sidebar')

  <main class="ml-0 lg:ml-64 pt-20 p-6 min-h-screen">
    <div class="mb-6">
      <h1 class="text-2xl font-semibold">{{ ($reportType ?? 'expenses') === 'income' ? 'Income Report' : 'Expense Report' }}</h1>
      <p class="text-sm text-slate-500">View financial reports by company or across all companies.</p>
    </div>

    <form method="GET" action="{{ route('reports') }}" class="bg-white border border-slate-200 rounded-lg p-4 mb-6">
      <div class="flex flex-col lg:flex-row gap-4 items-end">
        <div class="w-full lg:w-56">
          <label for="report_type" class="block text-sm font-medium text-slate-700 mb-1">Report type</label>
          <select id="report_type" name="report_type" class="w-full px-3 py-2 border border-slate-200 rounded-md text-sm bg-white">
            <option value="expenses" @selected(($reportType ?? 'expenses') === 'expenses')>Expenses</option>
            <option value="income" @selected(($reportType ?? '') === 'income')>Income Statement</option>
            <option value="balance" @selected(($reportType ?? '') === 'balance')>Balance Sheet</option>
          </select>
        </div>

        <div class="w-full lg:w-56">
          <label for="scope" class="block text-sm font-medium text-slate-700 mb-1">Report scope</label>
          <select id="scope" name="scope" class="w-full px-3 py-2 border border-slate-200 rounded-md text-sm bg-white">
            <option value="all" @selected($selectedScope === 'all')>All companies</option>
            <option value="company" @selected($selectedScope === 'company')>Single company</option>
          </select>
        </div>

        <div id="companyField" class="w-full lg:w-64 {{ $selectedScope === 'company' ? '' : 'hidden' }}">
          <label for="company_id" class="block text-sm font-medium text-slate-700 mb-1">Company</label>
          <select id="company_id" name="company_id" class="w-full px-3 py-2 border border-slate-200 rounded-md text-sm bg-white">
            @foreach ($companies as $company)
              <option value="{{ $company->id }}" @selected((int) $selectedCompanyId === (int) $company->id)>{{ $company->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="ml-auto">
          <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-md text-sm">Generate</button>
        </div>
      </div>
    </form>

    @if (($reportType ?? 'expenses') === 'balance')
      <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('balance-sheet') }}" class="px-4 py-2 bg-white border border-slate-200 rounded-md text-sm text-slate-700">Preview Balance Sheet</a>
        <a href="{{ route('balance_sheet') }}" class="px-4 py-2 bg-brand-600 text-white rounded-md text-sm">Export Balance Sheet PDF</a>
      </div>
    @elseif (($reportType ?? 'expenses') === 'income')
      <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('income-statement') }}" class="px-4 py-2 bg-white border border-slate-200 rounded-md text-sm text-slate-700">Preview Income Statement</a>
        <a href="{{ route('income-statement-export') }}" class="px-4 py-2 bg-brand-600 text-white rounded-md text-sm">Export Income Statement PDF</a>
      </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
      <div class="bg-white border border-slate-200 rounded-lg p-4">
        <p class="text-xs uppercase tracking-wide text-slate-500">Scope</p>
        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $selectedCompanyName }}</p>
      </div>
      @if (($reportType ?? 'expenses') === 'expenses')
        <div class="bg-white border border-slate-200 rounded-lg p-4 flex flex-col justify-between">
          <p class="text-xs uppercase tracking-wide text-slate-500">Expense Count</p>
          <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($totals['expense_count']) }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-4 flex flex-col justify-between">
          <p class="text-xs uppercase tracking-wide text-slate-500">Gross Amount</p>
          <p class="mt-2 text-2xl font-semibold text-slate-900">TZS {{ number_format($totals['gross_amount'], 2) }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-4 flex flex-col justify-between">
          <p class="text-xs uppercase tracking-wide text-slate-500">Net Amount</p>
          <p class="mt-2 text-2xl font-semibold text-slate-900">TZS {{ number_format($totals['net_amount'], 2) }}</p>
        </div>
      @else
        <div class="bg-white border border-slate-200 rounded-lg p-4 flex flex-col justify-between">
          <p class="text-xs uppercase tracking-wide text-slate-500">Invoices</p>
          <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($incomeTotals['invoice_count'] ?? 0) }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-4 flex flex-col justify-between">
          <p class="text-xs uppercase tracking-wide text-slate-500">Subtotal</p>
          <p class="mt-2 text-2xl font-semibold text-slate-900">TZS {{ number_format($incomeTotals['subtotal'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-4 flex flex-col justify-between">
          <p class="text-xs uppercase tracking-wide text-slate-500">Total</p>
          <p class="mt-2 text-2xl font-semibold text-slate-900">TZS {{ number_format($incomeTotals['total'] ?? 0, 2) }}</p>
        </div>
      @endif
    </div>

    @if (($reportType ?? 'expenses') === 'balance')
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-lg p-4">
          <p class="text-xs uppercase tracking-wide text-slate-500">Assets</p>
          <p class="mt-2 text-2xl font-semibold text-slate-900">TZS {{ number_format($balance['assets'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-4">
          <p class="text-xs uppercase tracking-wide text-slate-500">Liabilities</p>
          <p class="mt-2 text-2xl font-semibold text-slate-900">TZS {{ number_format($balance['liabilities'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg p-4">
          <p class="text-xs uppercase tracking-wide text-slate-500">Equity</p>
          <p class="mt-2 text-2xl font-semibold text-slate-900">TZS {{ number_format($balance['equity'] ?? 0, 2) }}</p>
        </div>
      </div>

      <div class="bg-white border border-slate-200 rounded-lg overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-200">
          <h3 class="text-lg font-semibold">Assets Breakdown</h3>
          <p class="text-sm text-slate-500">Cash and Accounts Receivable</p>
        </div>
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="p-3 bg-slate-50 rounded">
            <p class="text-sm text-slate-500">Cash</p>
            <p class="mt-1 text-lg font-semibold">TZS {{ number_format($balance['cash'] ?? 0, 2) }}</p>
          </div>
          <div class="p-3 bg-slate-50 rounded">
            <p class="text-sm text-slate-500">Receivables</p>
            <p class="mt-1 text-lg font-semibold">TZS {{ number_format($balance['receivables'] ?? 0, 2) }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white border border-slate-200 rounded-lg overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-200">
          <h3 class="text-lg font-semibold">Liabilities Breakdown</h3>
          <p class="text-sm text-slate-500">Accounts Payable</p>
        </div>
        <div class="p-4">
          <p class="text-sm text-slate-500">Total Payables</p>
          <p class="mt-1 text-lg font-semibold">TZS {{ number_format($balance['payables'] ?? $balance['liabilities'] ?? 0, 2) }}</p>
        </div>
      </div>
    @endif

    @if (($reportType ?? 'expenses') === 'pnl' || ($reportType ?? '') === 'pl' || ($reportType ?? '') === 'profit')
      <div class="bg-white border border-slate-200 rounded-lg p-4 mb-6">
        <h3 class="text-lg font-semibold mb-3">Profit & Loss (last 12 months)</h3>
        <canvas id="pnlChart" height="120"></canvas>
      </div>

      <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        <div class="p-4 border-b border-slate-200">
          <h3 class="text-lg font-semibold">P&L Table</h3>
          <p class="text-sm text-slate-500">Revenue, Expenses and Profit by month.</p>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
              <tr>
                <th class="px-4 py-3 text-left">Month</th>
                <th class="px-4 py-3 text-right">Revenue</th>
                <th class="px-4 py-3 text-right">Expenses</th>
                <th class="px-4 py-3 text-right">Profit</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @foreach (($plLabels ?? []) as $i => $label)
                <tr>
                  <td class="px-4 py-3">{{ $label }}</td>
                  <td class="px-4 py-3 text-right">TZS {{ number_format($plRevenue[$i] ?? 0, 2) }}</td>
                  <td class="px-4 py-3 text-right">TZS {{ number_format($plExpenses[$i] ?? 0, 2) }}</td>
                  <td class="px-4 py-3 text-right">TZS {{ number_format(($plProfit[$i] ?? 0), 2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif
    @if ($selectedScope === 'all' && ($reportType ?? 'expenses') === 'expenses')
      <div class="bg-white border border-slate-200 rounded-lg overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-200">
          <h3 class="text-lg font-semibold">Company Breakdown</h3>
          <p class="text-sm text-slate-500">Totals for each company across the whole business.</p>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-slate-50">
              <tr>
                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Expenses</th>
                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Gross</th>
                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">VAT</th>
                <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Net</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @forelse ($companyBreakdown as $row)
                <tr>
                  <td class="px-4 py-3 text-sm font-medium">{{ $row['company_name'] }}</td>
                  <td class="px-4 py-3 text-sm">{{ number_format($row['expense_count']) }}</td>
                  <td class="px-4 py-3 text-sm">TZS {{ number_format($row['gross_amount'], 2) }}</td>
                  <td class="px-4 py-3 text-sm">TZS {{ number_format($row['vat_amount'], 2) }}</td>
                  <td class="px-4 py-3 text-sm">TZS {{ number_format($row['net_amount'], 2) }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="px-4 py-6 text-sm text-slate-500">No expense data found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @endif

      @if ($selectedScope === 'all' && ($reportType ?? 'expenses') === 'income')
        <div class="bg-white border border-slate-200 rounded-lg overflow-hidden mb-6">
          <div class="p-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold">Company Income Breakdown</h3>
            <p class="text-sm text-slate-500">Totals for each company (invoices).</p>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full">
              <thead class="bg-slate-50">
                <tr>
                  <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Company</th>
                  <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Invoices</th>
                  <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Subtotal</th>
                  <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Tax</th>
                  <th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Total</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse ($companyIncomeBreakdown as $row)
                  <tr>
                    <td class="px-4 py-3 text-sm font-medium">{{ $row['company_name'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ number_format($row['invoice_count']) }}</td>
                    <td class="px-4 py-3 text-sm">TZS {{ number_format($row['subtotal'], 2) }}</td>
                    <td class="px-4 py-3 text-sm">TZS {{ number_format($row['tax'], 2) }}</td>
                    <td class="px-4 py-3 text-sm">TZS {{ number_format($row['total'], 2) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="5" class="px-4 py-6 text-sm text-slate-500">No income data found.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      @endif

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
      <div class="p-4 border-b border-slate-200">
        @if (($reportType ?? 'expenses') === 'expenses')
          <h3 class="text-lg font-semibold">Expense Details</h3>
          <p class="text-sm text-slate-500">Latest expense records in the selected scope.</p>
        @else
          <h3 class="text-lg font-semibold">Invoice Details</h3>
          <p class="text-sm text-slate-500">Latest invoices in the selected scope.</p>
        @endif
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-slate-50">
            <tr class="text-slate-500 text-xs uppercase">
              @if (($reportType ?? 'expenses') === 'expenses')
                <th class="px-4 py-3 text-left">Expense No</th>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Company</th>
                <th class="px-4 py-3 text-left">Department</th>
                <th class="px-4 py-3 text-left">Category</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-right">Gross</th>
                <th class="px-4 py-3 text-right">VAT</th>
                <th class="px-4 py-3 text-right">Net</th>
              @else
                <th class="px-4 py-3 text-left">Invoice No</th>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Company</th>
                <th class="px-4 py-3 text-left">Client</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-right">Subtotal</th>
                <th class="px-4 py-3 text-right">Tax</th>
                <th class="px-4 py-3 text-right">Total</th>
              @endif
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
              @if (($reportType ?? 'expenses') === 'expenses')
                @forelse ($expenseRows as $row)
                  <tr>
                    <td class="px-4 py-3 text-sm font-medium">{{ $row['expense_number'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row['expense_date'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row['company_name'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row['department_name'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row['category'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row['status'] }}</td>
                    <td class="px-4 py-3 text-sm text-right">TZS {{ number_format($row['gross_amount'], 2) }}</td>
                    <td class="px-4 py-3 text-sm text-right">TZS {{ number_format($row['vat_amount'], 2) }}</td>
                    <td class="px-4 py-3 text-sm text-right">TZS {{ number_format($row['net_amount'], 2) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="9" class="px-4 py-6 text-sm text-slate-500">No expenses found for this scope.</td></tr>
                @endforelse
              @else
                @forelse ($incomeRows as $row)
                  <tr>
                    <td class="px-4 py-3 text-sm font-medium">{{ $row['invoice_number'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row['invoice_date'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row['company_name'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row['client'] }}</td>
                    <td class="px-4 py-3 text-sm">{{ $row['status'] }}</td>
                    <td class="px-4 py-3 text-sm text-right">TZS {{ number_format($row['subtotal'], 2) }}</td>
                    <td class="px-4 py-3 text-sm text-right">TZS {{ number_format($row['tax'], 2) }}</td>
                    <td class="px-4 py-3 text-sm text-right">TZS {{ number_format($row['total'], 2) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="8" class="px-4 py-6 text-sm text-slate-500">No invoices found for this scope.</td></tr>
                @endforelse
              @endif
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script>
    const scopeSelect = document.getElementById('scope');
    const companyField = document.getElementById('companyField');

    function toggleCompanyField() {
      companyField.classList.toggle('hidden', scopeSelect.value !== 'company');
    }

    scopeSelect.addEventListener('change', toggleCompanyField);
    toggleCompanyField();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    (function(){
      const reportType = '{{ $reportType ?? 'expenses' }}';
      if (['pnl','pl','profit'].includes(reportType)) {
        const labels = {!! json_encode($plLabels ?? []) !!};
        const revenue = {!! json_encode($plRevenue ?? []) !!};
        const expenses = {!! json_encode($plExpenses ?? []) !!};
        const profit = {!! json_encode($plProfit ?? []) !!};

        const ctx = document.getElementById('pnlChart').getContext('2d');
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels,
            datasets: [
              { label: 'Revenue', data: revenue, backgroundColor: 'rgba(56,189,248,0.6)' },
              { label: 'Expenses', data: expenses, backgroundColor: 'rgba(249,115,22,0.6)' },
              { label: 'Profit', data: profit, type: 'line', borderColor: 'rgba(34,197,94,0.9)', backgroundColor: 'rgba(34,197,94,0.2)', tension: 0.3 }
            ]
          },
          options: { responsive:true, maintainAspectRatio:false, scales:{ y:{ ticks:{ callback: v => 'TZS ' + Number(v).toLocaleString() } } } }
        });
      }
    })();
  </script>

  @include('components.modal')
  @include('components.alert')
  @include('components.confirm')
</body>
</html>
