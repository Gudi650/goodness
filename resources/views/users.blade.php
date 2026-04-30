<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Goodness ERP</title>
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
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="utf-8">
                        <meta name="viewport" content="width=device-width,initial-scale=1">
                        <title>Users — Goodness Group</title>
                        <script src="https://cdn.tailwindcss.com"></script>
                        <script>
                            tailwind.config = { theme: { extend: { colors: { brand: {50:'#f0fdf4',100:'#dcfce7',500:'#22c55e',600:'#16a34a',700:'#15803d'} }, fontFamily:{ sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] } } } }
                        </script>
                        <link rel="preconnect" href="https://fonts.googleapis.com">
                        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
                        <style>body{font-family:Inter, sans-serif}h1,h2,nav,button{font-family:Outfit, sans-serif}</style>
                    </head>
                    <body class="bg-slate-50 text-slate-800">
                        <div class="min-h-screen md:flex">
                            <aside class="hidden md:block w-64 bg-white border-r border-slate-200 p-6">
                                <div class="text-sm font-semibold">Goodness Group</div>
                                <nav class="mt-6 space-y-1">
                                    <a href="/dashboard" class="block px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-md">Dashboard</a>
                                    <a href="/users" class="block px-3 py-2 text-sm bg-green-50 text-green-700 rounded-l-md">Users</a>
                                </nav>
                            </aside>

                            <main class="flex-1 p-6">
                                <div class="mb-6">
                                    <h1 class="text-2xl font-semibold">Users & Roles</h1>
                                    <p class="text-sm text-slate-500">Manage user accounts and permissions</p>
                                </div>

                                <div class="flex items-center gap-3 mb-4">
                                    <input id="searchUser" type="text" placeholder="Search users..." class="flex-1 px-4 py-2 border border-slate-200 rounded-md bg-white" />
                                    <button id="addUserBtn" class="px-4 py-2 bg-brand-600 text-white rounded-md">Add User</button>
                                </div>

                                <div class="bg-white border border-slate-200 rounded-lg overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Name</th>
                                                <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-left">Email</th>
                                                <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-center">Role</th>
                                                <th class="text-xs text-slate-500 uppercase tracking-wider px-4 py-3 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="usersTbody" class="bg-white divide-y divide-slate-100"></tbody>
                                    </table>
                                </div>

                                <!-- Modal -->
                                <div id="userModal" class="hidden fixed inset-0 z-40 flex items-center justify-center p-4">
                                    <div class="bg-white border border-slate-200 rounded-lg p-6 w-full max-w-lg">
                                        <h3 class="text-lg font-semibold mb-4">Add User</h3>
                                        <div class="space-y-3">
                                            <input id="userName" placeholder="Full name" class="w-full px-4 py-2 border border-slate-200 rounded-md" />
                                            <input id="userEmail" placeholder="Email" class="w-full px-4 py-2 border border-slate-200 rounded-md" />
                                            <select id="userRole" class="w-full px-4 py-2 border border-slate-200 rounded-md"><option>Administrator</option><option>Manager</option><option>Employee</option></select>
                                        </div>
                                        <div class="mt-4 flex justify-end gap-2">
                                            <button onclick="closeUserModal()" class="px-3 py-2 border border-slate-200 rounded-md">Cancel</button>
                                            <button onclick="saveUser()" class="px-3 py-2 bg-brand-600 text-white rounded-md">Save</button>
                                        </div>
                                    </div>
                                </div>

                            </main>
                        </div>

                        <script>
                            const users = [ { id:1, name:'John Doe', email:'john@example.com', role:'Administrator' }, { id:2, name:'Jane Smith', email:'jane@example.com', role:'Manager' }, { id:3, name:'Ali Hassan', email:'ali@example.com', role:'Employee' } ];
                            function renderUsers(filter=''){ const tbody = document.getElementById('usersTbody'); tbody.innerHTML=''; const f = filter.toLowerCase(); users.filter(u=> u.name.toLowerCase().includes(f) || u.email.toLowerCase().includes(f)).forEach(u=>{ const tr=document.createElement('tr'); tr.innerHTML=`<td class="px-4 py-3 text-sm">${u.name}</td><td class="px-4 py-3 text-sm">${u.email}</td><td class="px-4 py-3 text-sm text-center"><span class="inline-block px-2 py-1 bg-slate-50 text-slate-700 rounded-md text-xs">${u.role}</span></td><td class="px-4 py-3 text-sm text-center"><button class="px-2 py-1 border border-slate-200 rounded-md">Edit</button></td>`; tbody.appendChild(tr); }) }
                            document.getElementById('searchUser').addEventListener('input', e=> renderUsers(e.target.value)); document.getElementById('addUserBtn').addEventListener('click', ()=> document.getElementById('userModal').classList.remove('hidden'));
                            function closeUserModal(){ document.getElementById('userModal').classList.add('hidden') }
                            function saveUser(){ const name=document.getElementById('userName').value.trim(); const email=document.getElementById('userEmail').value.trim(); const role=document.getElementById('userRole').value; if(!name||!email){alert('Please provide name and email');return} users.push({ id:users.length+1, name, email, role }); renderUsers(); closeUserModal(); }
                            renderUsers();
                        </script>
                    </body>
                    </html>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto px-8 py-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold">Users</h1>
                        <p class="text-slate-400 mt-1">Manage user accounts and roles</p>
                    </div>
                    <button onclick="openAddUserModal()" class="bg-brand text-base px-6 py-2 rounded-md hover:bg-sky-600 font-medium">
                        Add User
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="bg-surface border border-slate-700 rounded-md p-4 mb-6">
                    <input 
                        type="text" 
                        id="searchInput"
                        placeholder="Search users by name or email..."
                        onkeyup="filterTable()"
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-500 focus:outline-none focus:border-brand"
                    >
                </div>

                <!-- Users Table -->
                <div class="bg-surface border border-slate-700 rounded-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="usersTable">
                            <thead class="bg-slate-800 border-b border-slate-700">
                                <tr class="text-slate-400">
                                    <th class="text-left py-4 px-6 font-medium">Full Name</th>
                                    <th class="text-left py-4 px-6 font-medium">Email</th>
                                    <th class="text-left py-4 px-6 font-medium">Role</th>
                                    <th class="text-left py-4 px-6 font-medium">Assigned Company</th>
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

    <!-- Add User Modal -->
    <div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-surface border border-slate-700 rounded-md w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Add New User</h2>
                <button onclick="closeAddUserModal()" class="text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="addUserForm" onsubmit="handleAddUser(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Full Name</label>
                    <input type="text" required class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-500 focus:outline-none focus:border-brand" placeholder="Enter full name">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Email</label>
                    <input type="email" required class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-500 focus:outline-none focus:border-brand" placeholder="user@email.com">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Role</label>
                    <select required class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white focus:outline-none focus:border-brand">
                        <option value="">Select role</option>
                        <option value="Chairman">Chairman</option>
                        <option value="Manager">Manager</option>
                        <option value="Employee">Employee</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Assigned Company</label>
                    <select required class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white focus:outline-none focus:border-brand">
                        <option value="">Select company</option>
                        <option>Goodness Group HQ</option>
                        <option>Goodness Tanzania Ltd</option>
                        <option>Goodness Kenya Ltd</option>
                        <option>Goodness Uganda Ltd</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-300">Password</label>
                    <input type="password" required class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-md text-white placeholder-slate-500 focus:outline-none focus:border-brand" placeholder="Enter secure password">
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeAddUserModal()" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-md hover:bg-slate-600 font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-brand text-base rounded-md hover:bg-sky-600 font-medium">
                        Add User
                    </button>
                </div>
            </form>

            <div id="successMsg" class="mt-4 p-3 bg-green-900 border border-green-700 rounded-md text-green-100 hidden text-sm">
                User added successfully!
            </div>
        </div>
    </div>

    <script>
        const usersData = [
            { id: 1, name: 'John Doe', email: 'john@goodness.com', role: 'Chairman', company: 'Goodness Group HQ', status: 'Active' },
            { id: 2, name: 'Jane Smith', email: 'jane@goodness.com', role: 'Manager', company: 'Goodness Tanzania Ltd', status: 'Active' },
            { id: 3, name: 'David Johnson', email: 'david@goodness.com', role: 'Manager', company: 'Goodness Kenya Ltd', status: 'Active' },
            { id: 4, name: 'Sarah Wilson', email: 'sarah@goodness.com', role: 'Employee', company: 'Goodness Tanzania Ltd', status: 'Active' },
            { id: 5, name: 'Michael Brown', email: 'michael@goodness.com', role: 'Employee', company: 'Goodness Uganda Ltd', status: 'Active' },
            { id: 6, name: 'Emma Davis', email: 'emma@goodness.com', role: 'Manager', company: 'Goodness Uganda Ltd', status: 'Inactive' },
        ];

        function getRoleBadgeColor(role) {
            switch (role) {
                case 'Chairman': return 'bg-blue-900 text-blue-100';
                case 'Manager': return 'bg-amber-900 text-amber-100';
                case 'Employee': return 'bg-slate-700 text-slate-200';
                default: return 'bg-slate-700 text-slate-300';
            }
        }

        function renderTable() {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = usersData.map(user => `
                <tr class="border-b border-slate-700 hover:bg-slate-800">
                    <td class="py-4 px-6 font-medium">${user.name}</td>
                    <td class="py-4 px-6 text-slate-300">${user.email}</td>
                    <td class="py-4 px-6">
                        <span class="px-2 py-1 ${getRoleBadgeColor(user.role)} rounded text-xs font-medium">${user.role}</span>
                    </td>
                    <td class="py-4 px-6 text-slate-300">${user.company}</td>
                    <td class="text-center py-4 px-6">
                        <span class="px-2 py-1 ${user.status === 'Active' ? 'bg-green-900 text-green-100' : 'bg-slate-700 text-slate-300'} rounded text-xs font-medium">${user.status}</span>
                    </td>
                    <td class="text-center py-4 px-6">
                        <div class="flex gap-2 justify-center">
                            <button onclick="editUser(${user.id})" class="text-brand hover:text-sky-400 text-xs font-medium">Edit</button>
                            <button onclick="deleteUser(${user.id})" class="text-slate-400 hover:text-white text-xs font-medium">Remove</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function filterTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                const matches = name.includes(searchValue) || email.includes(searchValue);
                row.style.display = matches ? '' : 'none';
            });
        }

        function openAddUserModal() {
            document.getElementById('addUserModal').classList.remove('hidden');
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').classList.add('hidden');
            document.getElementById('addUserForm').reset();
            document.getElementById('successMsg').classList.add('hidden');
        }

        function handleAddUser(event) {
            event.preventDefault();
            document.getElementById('successMsg').classList.remove('hidden');
            setTimeout(() => {
                closeAddUserModal();
            }, 1500);
        }

        function editUser(id) {
            alert('Edit functionality for user ' + id);
        }

        function deleteUser(id) {
            if (confirm('Remove this user?')) {
                const index = usersData.findIndex(u => u.id === id);
                if (index > -1) usersData.splice(index, 1);
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
