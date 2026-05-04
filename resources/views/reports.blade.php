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
    <div class="mb-6"><h1 class="text-2xl font-semibold">Reports & Analytics</h1><p class="text-sm text-slate-500">Generate and analyze business reports</p></div>
    <div class="bg-white border border-slate-200 rounded-lg p-4 mb-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
        <select id="reportType" class="px-3 py-2 border border-slate-200 rounded-md text-sm bg-white"><option value="financial">Financial Summary</option><option value="hr">HR Summary</option><option value="sales">Sales Summary</option></select>
      </div>
      <button onclick="generateReport()" class="px-4 py-2 bg-brand-600 text-white rounded-md text-sm">Generate Report</button>
    </div>

    <div id="reportResults" class="hidden bg-white border border-slate-200 rounded-lg overflow-hidden">
      <div class="p-4">
        <h3 id="reportTitle" class="text-lg font-semibold mb-4"></h3>
        <div class="overflow-x-auto"><table id="reportTable" class="min-w-full"><thead class="bg-slate-50"></thead><tbody id="reportBody" class="divide-y divide-slate-100"></tbody></table></div>
      </div>
    </div>
  </main>

  <script>
    const reportData = {
      financial: { title:'Financial Summary', headers:['Company','Revenue','Expenses'], rows:[['Goodness Tanzania Ltd','TZS 5,250,000','TZS 1,200,000']] },
      hr: { title:'HR Summary', headers:['Company','Employees','Active'], rows:[['Goodness Kenya Ltd','215','205']] },
      sales: { title:'Sales Summary', headers:['Company','Orders','Value'], rows:[['Goodness Uganda Ltd','18','TZS 3,800,000']] }
    };
    function generateReport(){
      const key = document.getElementById('reportType').value;
      const report = reportData[key];
      document.getElementById('reportTitle').textContent = report.title;
      document.querySelector('#reportTable thead').innerHTML = `<tr>${report.headers.map(h => `<th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">${h}</th>`).join('')}</tr>`;
      document.getElementById('reportBody').innerHTML = report.rows.map(r => `<tr>${r.map(v => `<td class="px-4 py-3 text-sm">${v}</td>`).join('')}</tr>`).join('');
      document.getElementById('reportResults').classList.remove('hidden');
    }
  </script>

  @include('components.modal')
  @include('components.alert')
  @include('components.confirm')
</body>
</html>
