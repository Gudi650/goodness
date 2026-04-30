<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Reports — Goodness Group</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors: { brand: {50:'#f0fdf4',100:'#dcfce7',500:'#22c55e',600:'#16a34a',700:'#15803d'} }, fontFamily:{ sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] } } } }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
  <style>body{font-family:Inter, sans-serif}h1,h2,nav,button{font-family:Outfit, sans-serif}.mono{font-family:ui-monospace,monospace}</style>
</head>
<body class="bg-slate-50 text-slate-800">
  <div class="min-h-screen md:flex">
    <aside class="hidden md:block w-64 bg-white border-r border-slate-200 p-6">
      <div class="text-sm font-semibold">Goodness Group</div>
      <nav class="mt-6 space-y-1"><a href="/dashboard" class="block px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-md">Dashboard</a><a href="/reports" class="block px-3 py-2 text-sm bg-green-50 text-green-700 rounded-l-md">Reports</a></nav>
    </aside>

    <main class="flex-1 p-6">
    @include('components.topbar')
    @include('components.sidebar')
    <main class="ml-0 lg:ml-64 pt-16 p-6">

      <div class="bg-white border border-slate-200 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
          <div>
            <label class="block text-xs text-slate-500 mb-2">Company</label>
            <select id="companyFilter" class="w-full px-3 py-2 border border-slate-200 rounded-md text-sm bg-white">
              <option>All Companies</option>
              <option>Goodness Tanzania Ltd</option>
              <option>Goodness Kenya Ltd</option>
              <option>Goodness Uganda Ltd</option>
            </select>
          </div>
          <div>
            <label class="block text-xs text-slate-500 mb-2">Start Date</label>
            <input type="date" id="startDate" class="w-full px-3 py-2 border border-slate-200 rounded-md text-sm" />
          </div>
          <div>
            <label class="block text-xs text-slate-500 mb-2">End Date</label>
            <input type="date" id="endDate" class="w-full px-3 py-2 border border-slate-200 rounded-md text-sm" />
          </div>
          <div>
            <label class="block text-xs text-slate-500 mb-2">Report Type</label>
            <select id="reportType" class="w-full px-3 py-2 border border-slate-200 rounded-md text-sm bg-white">
              <option value="financial">Financial Summary</option>
              <option value="hr">HR Summary</option>
              <option value="sales">Sales Summary</option>
              <option value="inventory">Inventory Summary</option>
            </select>
          </div>
        </div>
        <div class="flex gap-2 flex-wrap">
          <button onclick="generateReport()" class="px-4 py-2 bg-brand-600 text-white rounded-md text-sm">Generate Report</button>
          <button onclick="exportPDF()" class="px-4 py-2 border border-slate-200 rounded-md text-sm">Export PDF</button>
          <button onclick="exportExcel()" class="px-4 py-2 border border-slate-200 rounded-md text-sm">Export Excel</button>
        </div>
      </div>

      <div class="bg-white border border-slate-200 rounded-lg p-4 mb-6">
        <h3 class="text-lg font-semibold mb-4">Monthly Revenue by Company</h3>
        <canvas id="revenueChart" height="80" style="max-width:100%"></canvas>
      </div>

      <div id="reportResults" class="hidden bg-white border border-slate-200 rounded-lg overflow-hidden">
        <div class="p-4">
          <h3 id="reportTitle" class="text-lg font-semibold mb-4"></h3>
          <div class="overflow-x-auto">
            <table id="reportTable" class="min-w-full">
              <thead class="bg-slate-50">
              </thead>
              <tbody id="reportBody" class="bg-white divide-y divide-slate-100">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>

  @include('components.modal')
  @include('components.alert')
  @include('components.confirm')
  <script>
    const reportData = {
      financial: { title:'Financial Summary Report', headers:['Company','Total Revenue','Total Expenses','Net Profit','Profit Margin'], rows:[ {company:'Goodness Tanzania Ltd',revenue:'TZS 5,250,000',expenses:'TZS 1,200,000',profit:'TZS 4,050,000',margin:'77.1%'}, {company:'Goodness Kenya Ltd',revenue:'TZS 3,850,000',expenses:'TZS 850,000',profit:'TZS 3,000,000',margin:'77.9%'}, {company:'Goodness Uganda Ltd',revenue:'TZS 2,450,000',expenses:'TZS 550,000',profit:'TZS 1,900,000',margin:'77.6%'} ] },
      hr: { title:'HR Summary Report', headers:['Company','Total Employees','Active','Inactive','Avg Salary'], rows:[ {company:'Goodness Tanzania Ltd',total:'320',active:'310',inactive:'10',avgSalary:'TZS 2,100,000'}, {company:'Goodness Kenya Ltd',total:'215',active:'205',inactive:'10',avgSalary:'TZS 1,950,000'}, {company:'Goodness Uganda Ltd',total:'142',active:'138',inactive:'4',avgSalary:'TZS 1,800,000'} ] },
      sales: { title:'Sales Summary Report', headers:['Company','Total Orders','Completed','Pending','Total Value'], rows:[ {company:'Goodness Tanzania Ltd',total:'45',completed:'38',pending:'7',value:'TZS 8,500,000'}, {company:'Goodness Kenya Ltd',total:'32',completed:'28',pending:'4',value:'TZS 6,200,000'}, {company:'Goodness Uganda Ltd',total:'18',completed:'15',pending:'3',value:'TZS 3,800,000'} ] },
      inventory: { title:'Inventory Summary Report', headers:['Company','Total Products','In Stock','Low Stock','Total Value'], rows:[ {company:'Goodness Tanzania Ltd',total:'156',inStock:'142',lowStock:'14',value:'TZS 45,200,000'}, {company:'Goodness Kenya Ltd',total:'98',inStock:'89',lowStock:'9',value:'TZS 28,500,000'}, {company:'Goodness Uganda Ltd',total:'67',inStock:'61',lowStock:'6',value:'TZS 18,300,000'} ] }
    };

    function drawChart() {
      const canvas = document.getElementById('revenueChart');
      const ctx = canvas.getContext('2d');
      const companies = ['Tanzania','Kenya','Uganda'];
      const months = ['Jan','Feb','Mar','Apr'];
      const data = { Tanzania:[3500000,3800000,4200000,5250000], Kenya:[2800000,3000000,3500000,3850000], Uganda:[1500000,1800000,2000000,2450000] };
      const colors = ['#16a34a','#22c55e','#86efac'];
      const padding = 60;
      const chartWidth = canvas.width - 2*padding;
      const chartHeight = canvas.height - padding - 40;
      const barWidth = chartWidth/(months.length*companies.length+months.length);

      ctx.fillStyle = '#f8fafc';
      ctx.fillRect(0,0,canvas.width,canvas.height);
      ctx.strokeStyle = '#e2e8f0';
      ctx.lineWidth = 1;
      ctx.beginPath();
      ctx.moveTo(padding,padding);
      ctx.lineTo(padding,canvas.height-40);
      ctx.lineTo(canvas.width-20,canvas.height-40);
      ctx.stroke();

      ctx.fillStyle = '#64748b';
      ctx.font = '12px Inter';
      ctx.textAlign = 'right';
      for(let i=0;i<=5;i++){
        const y = canvas.height-40-(i*chartHeight/5);
        const value = (i*1300000).toLocaleString();
        ctx.fillText(value,padding-10,y+4);
        ctx.strokeStyle = '#cbd5e1';
        ctx.beginPath();
        ctx.moveTo(padding,y);
        ctx.lineTo(canvas.width-20,y);
        ctx.stroke();
      }

      ctx.textAlign = 'center';
      let xPos = padding+barWidth*1.5;
      months.forEach(month=>{ ctx.fillText(month,xPos+barWidth*1.5,canvas.height-20); xPos+=barWidth*(companies.length+1); });

      xPos = padding+barWidth;
      months.forEach((month,monthIndex)=>{
        companies.forEach((company,companyIndex)=>{
          const value = data[company][monthIndex];
          const barHeight = (value/6500000)*chartHeight;
          const yPos = canvas.height-40-barHeight;
          ctx.fillStyle = colors[companyIndex];
          ctx.fillRect(xPos,yPos,barWidth-2,barHeight);
          xPos+=barWidth;
        });
        xPos+=barWidth;
      });

      let legendX = canvas.width-180;
      ctx.textAlign = 'left';
      companies.forEach((company,index)=>{
        ctx.fillStyle = colors[index];
        ctx.fillRect(legendX,20+index*20,12,12);
        ctx.fillStyle = '#475569';
        ctx.fillText(company,legendX+20,30+index*20);
      });
    }

    function generateReport(){
      const reportType = document.getElementById('reportType').value;
      const report = reportData[reportType];
      document.getElementById('reportTitle').textContent = report.title;
      const thead = document.querySelector('#reportTable thead');
      thead.innerHTML = '<tr class="bg-slate-50">'+report.headers.map(h=>`<th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left font-medium">${h}</th>`).join('')+'</tr>';
      const tbody = document.getElementById('reportBody');
      tbody.innerHTML = report.rows.map(row=>'<tr class="hover:bg-slate-50">'+Object.values(row).map(val=>`<td class="px-4 py-3 text-sm">${val}</td>`).join('')+'</tr>').join('');
      document.getElementById('reportResults').classList.remove('hidden');
    }

    function exportPDF(){ alert('Export to PDF functionality'); }
    function exportExcel(){ alert('Export to Excel functionality'); }

    window.addEventListener('load', drawChart);
    window.addEventListener('resize', drawChart);
  </script>
</body>
</html>
