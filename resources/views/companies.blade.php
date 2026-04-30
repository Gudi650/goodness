<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companies - Goodness ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .mono { font-family: 'IBM Plex Mono', monospace; }
        .sidebar-collapsed #sidebarNav { @apply hidden; }
        .sidebar-collapsed #sidebarToggle svg:first-child { @apply hidden; }
        .sidebar-collapsed #sidebarToggle svg:last-child { @apply block; }
    </style>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: '#0ea5e9',
                        surface: '#1e293b',
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="utf-8">
                            <meta name="viewport" content="width=device-width,initial-scale=1">
                            <title>Companies — Goodness Group</title>
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
                                    <nav class="mt-6 space-y-1">
                                        <a href="/dashboard" class="block px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-md">Dashboard</a>
                                        <a href="/companies" class="block px-3 py-2 text-sm bg-green-50 text-green-700 rounded-l-md">Companies</a>
                                    </nav>
                                </aside>

                                <main class="flex-1 p-6">
                                    <div class="mb-6">
                                        <h1 class="text-2xl font-semibold">Companies</h1>
                                        <p class="text-sm text-slate-500">Manage your subsidiaries and branches</p>
                                    </div>

                                    <div class="flex items-center gap-3 mb-4">
                                        <input id="search" type="text" placeholder="Search companies..." class="flex-1 px-4 py-2 border border-slate-200 rounded-md bg-white" />
                                        <button id="addCompanyBtn" class="px-4 py-2 bg-brand-600 text-white rounded-md">Add Company</button>
                                    </div>

                                    <div class="bg-white border border-slate-200 rounded-lg overflow-x-auto">
                                        <table class="min-w-full">
                                            <thead class="bg-slate-50">
                                                <tr>
                                                    <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Name</th>
                                                    <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Country</th>
                                                    <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-right">Revenue (TZS)</th>
                                                    <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-center">Status</th>
                                                    <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="companiesTbody" class="bg-white divide-y divide-slate-100"></tbody>
                                        </table>
                                    </div>
                                </main>
                            </div>

                            <!-- Modal -->
                            <div id="companyModal" class="hidden fixed inset-0 z-40 flex items-center justify-center p-4">
                                <div class="bg-white border border-slate-200 rounded-lg p-6 w-full max-w-lg">
                                    <h3 class="text-lg font-semibold mb-4">Add Company</h3>
                                    <div class="space-y-3">
                                        <input id="companyName" placeholder="Company name" class="w-full px-4 py-2 border border-slate-200 rounded-md" />
                                        <input id="companyCountry" placeholder="Country" class="w-full px-4 py-2 border border-slate-200 rounded-md" />
                                        <input id="companyRevenue" placeholder="Revenue" class="w-full px-4 py-2 border border-slate-200 rounded-md" />
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button onclick="closeCompanyModal()" class="px-3 py-2 border border-slate-200 rounded-md">Cancel</button>
                                        <button onclick="saveCompany()" class="px-3 py-2 bg-brand-600 text-white rounded-md">Save</button>
                                    </div>
                                </div>
                            </div>

                            <script>
                                const companiesData = [
                                    { id:1, name:'Goodness Tanzania Ltd', country:'Tanzania', revenue:1250000, status:'Active' },
                                    { id:2, name:'Goodness Kenya Ltd', country:'Kenya', revenue:850000, status:'Active' },
                                    { id:3, name:'Goodness Uganda Ltd', country:'Uganda', revenue:450000, status:'Inactive' }
                                ];

                                function formatTZS(n){ return 'TZS ' + n.toLocaleString(); }

                                function renderCompanies(filter=''){ const tbody = document.getElementById('companiesTbody'); tbody.innerHTML=''; const f = filter.toLowerCase(); companiesData.filter(c=> c.name.toLowerCase().includes(f) || c.country.toLowerCase().includes(f)).forEach(c=>{
                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `<td class="px-4 py-3 text-sm">${c.name}</td><td class="px-4 py-3 text-sm">${c.country}</td><td class="px-4 py-3 text-sm text-right mono">${formatTZS(c.revenue)}</td><td class="px-4 py-3 text-sm text-center"><span class="inline-block px-2 py-1 ${c.status==='Active'?'bg-green-50 text-green-700':'bg-slate-50 text-slate-600'} rounded-md text-xs">${c.status}</span></td><td class="px-4 py-3 text-sm text-center"><button class="px-2 py-1 border border-slate-200 rounded-md">Edit</button></td>`;
                                    tbody.appendChild(tr);
                                })}

                                document.getElementById('search').addEventListener('input', e=> renderCompanies(e.target.value));
                                document.getElementById('addCompanyBtn').addEventListener('click', ()=> document.getElementById('companyModal').classList.remove('hidden'));
                                function closeCompanyModal(){ document.getElementById('companyModal').classList.add('hidden') }
                                function saveCompany(){ const name = document.getElementById('companyName').value.trim(); const country = document.getElementById('companyCountry').value.trim(); const revenue = Number(document.getElementById('companyRevenue').value)||0; if(!name||!country){alert('Please fill required fields'); return}; companiesData.push({ id:companiesData.length+1, name, country, revenue, status:'Active' }); renderCompanies(); closeCompanyModal(); }

                                renderCompanies();
                            </script>
                        </body>
                        </html>
                                    <th class="text-left py-4 px-6 font-medium">Type</th>
                                    <th class="text-left py-4 px-6 font-medium">Industry</th>
                                    <th class="text-center py-4 px-6 font-medium">Status</th>
                                    <th class="text-center py-4 px-6 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Company Modal -->
    <div id="addCompanyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-surface border border-slate-700 rounded-md w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Add New Company</h2>
                <button onclick="closeAddCompanyModal()" class="text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="addCompanyForm" onsubmit="handleAddCompany(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Company Name</label>
                    <input type="text" required class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-500 focus:outline-none focus:border-brand" placeholder="Enter company name">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Parent Company</label>
                    <select class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white focus:outline-none focus:border-brand">
                        <option value="">None (Parent Company)</option>
                        <option>Goodness Group HQ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Industry</label>
                    <select class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white focus:outline-none focus:border-brand">
                        <option>Services</option>
                        <option>Retail</option>
                        <option>Technology</option>
                        <option>Manufacturing</option>
                        <option>Finance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Contact Person</label>
                    <input type="text" required class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-500 focus:outline-none focus:border-brand" placeholder="Full name">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Phone</label>
                    <input type="tel" required class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-500 focus:outline-none focus:border-brand" placeholder="+255 xxx xxx xxx">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Email</label>
                    <input type="email" required class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-500 focus:outline-none focus:border-brand" placeholder="company@email.com">
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeAddCompanyModal()" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-md hover:bg-slate-600 font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-brand text-base rounded-md hover:bg-sky-600 font-medium">
                        Add Company
                    </button>
                </div>
            </form>

            <div id="successMsg" class="mt-4 p-3 bg-green-900 border border-green-700 rounded-md text-green-100 hidden text-sm">
                Company added successfully!
            </div>
        </div>
    </div>

    <script>
        const companiesData = [
            { id: 1, name: 'Goodness Group HQ', type: 'Parent', industry: 'Services', status: 'Active' },
            { id: 2, name: 'Goodness Tanzania Ltd', type: 'Subsidiary', industry: 'Services', status: 'Active' },
            { id: 3, name: 'Goodness Kenya Ltd', type: 'Subsidiary', industry: 'Retail', status: 'Active' },
            { id: 4, name: 'Goodness Uganda Ltd', type: 'Subsidiary', industry: 'Services', status: 'Active' },
            { id: 5, name: 'Goodness Tech Solutions', type: 'Subsidiary', industry: 'Technology', status: 'Active' },
            { id: 6, name: 'Goodness Finance Ltd', type: 'Subsidiary', industry: 'Finance', status: 'Inactive' },
        ];

        function renderTable() {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = companiesData.map(company => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-4 px-6 font-medium">${company.name}</td>
                    <td class="py-4 px-6">
                        <span class="px-2 py-1 bg-slate-700 text-slate-200 rounded text-xs font-medium">${company.type}</span>
                    </td>
                    <td class="py-4 px-6">${company.industry}</td>
                    <td class="text-center py-4 px-6">
                        <span class="px-2 py-1 ${company.status === 'Active' ? 'bg-green-900 text-green-100' : 'bg-slate-700 text-slate-300'} rounded text-xs font-medium">${company.status}</span>
                    </td>
                    <td class="text-center py-4 px-6">
                        <div class="flex gap-2 justify-center">
                            <button onclick="editCompany(${company.id})" class="text-brand hover:text-sky-400 text-xs font-medium">Edit</button>
                            <button onclick="disableCompany(${company.id})" class="text-slate-400 hover:text-white text-xs font-medium">Disable</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function filterTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const typeValue = document.getElementById('typeFilter').value;
            const rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const type = row.cells[1].textContent.trim();
                const matchesSearch = name.includes(searchValue);
                const matchesType = !typeValue || type.includes(typeValue);
                row.style.display = matchesSearch && matchesType ? '' : 'none';
            });
        }

        function openAddCompanyModal() {
            document.getElementById('addCompanyModal').classList.remove('hidden');
        }

        function closeAddCompanyModal() {
            document.getElementById('addCompanyModal').classList.add('hidden');
            document.getElementById('addCompanyForm').reset();
            document.getElementById('successMsg').classList.add('hidden');
        }

        function handleAddCompany(event) {
            event.preventDefault();
            document.getElementById('successMsg').classList.remove('hidden');
            setTimeout(() => {
                closeAddCompanyModal();
            }, 1500);
        }

        function editCompany(id) {
            alert('Edit functionality for company ' + id);
        }

        function disableCompany(id) {
            if (confirm('Disable this company?')) {
                const company = companiesData.find(c => c.id === id);
                if (company) company.status = 'Inactive';
                renderTable();
            }
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/login';
            }
        }

        // Sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');
        });

        // Initialize
        renderTable();
    </script>
</body>
</html>
