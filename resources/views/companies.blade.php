<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Companies - Goodness Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: {50:'#f0fdf4',100:'#dcfce7',500:'#22c55e',600:'#16a34a',700:'#15803d'} }, fontFamily:{ sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] } } } }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:Inter,sans-serif}h1,h2,nav,button{font-family:Outfit,sans-serif}.mono{font-family:ui-monospace,monospace}</style>
</head>
<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-20 p-6 min-h-screen">
        <div class="mb-6"><h1 class="text-2xl font-semibold">Companies</h1><p class="text-sm text-slate-500">Manage your subsidiaries and branches</p></div>
        <div class="flex items-center gap-3 mb-4">
            <input id="search" type="text" placeholder="Search companies..." class="flex-1 px-4 py-2 border border-slate-200 rounded-md bg-white" />
            <button id="addCompanyBtn" class="px-4 py-2 bg-brand-600 text-white rounded-md">Add Company</button>
        </div>

        <div class="bg-white border border-slate-200 rounded-lg overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50"><tr><th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Name</th><th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Country</th><th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-right">Revenue (TZS)</th><th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-center">Status</th></tr></thead>
                <tbody id="companiesTbody" class="bg-white divide-y divide-slate-100"></tbody>
            </table>
        </div>
    </main>

    <script>
        const companiesData = [
            { id:1, name:'Goodness Tanzania Ltd', country:'Tanzania', revenue:1250000, status:'Active' },
            { id:2, name:'Goodness Kenya Ltd', country:'Kenya', revenue:850000, status:'Active' },
            { id:3, name:'Goodness Uganda Ltd', country:'Uganda', revenue:450000, status:'Inactive' }
        ];
        function formatTZS(n){ return 'TZS ' + n.toLocaleString(); }
        function renderCompanies(filter=''){
            const tbody = document.getElementById('companiesTbody');
            tbody.innerHTML = '';
            const f = filter.toLowerCase();
            companiesData.filter(c => c.name.toLowerCase().includes(f) || c.country.toLowerCase().includes(f)).forEach(c => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td class="px-4 py-3 text-sm">${c.name}</td><td class="px-4 py-3 text-sm">${c.country}</td><td class="px-4 py-3 text-sm text-right mono">${formatTZS(c.revenue)}</td><td class="px-4 py-3 text-sm text-center"><span class="inline-block px-2 py-1 ${c.status==='Active'?'bg-green-50 text-green-700':'bg-slate-50 text-slate-600'} rounded-md text-xs">${c.status}</span></td>`;
                tbody.appendChild(tr);
            });
        }
        document.getElementById('search').addEventListener('input', e => renderCompanies(e.target.value));
        renderCompanies();
    </script>

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')
</body>
</html>
