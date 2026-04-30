<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Users - Goodness Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: {50:'#f0fdf4',100:'#dcfce7',500:'#22c55e',600:'#16a34a',700:'#15803d'} }, fontFamily:{ sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] } } } }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:Inter,sans-serif}h1,h2,nav,button{font-family:Outfit,sans-serif}</style>
</head>
<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-20 p-6 min-h-screen">
        <div class="mb-6"><h1 class="text-2xl font-semibold">Users & Roles</h1><p class="text-sm text-slate-500">Manage user accounts and permissions</p></div>
        <div class="flex items-center gap-3 mb-4"><input id="searchUser" type="text" placeholder="Search users..." class="flex-1 px-4 py-2 border border-slate-200 rounded-md bg-white" /></div>
        <div class="bg-white border border-slate-200 rounded-lg overflow-x-auto">
            <table class="min-w-full"><thead class="bg-slate-50"><tr><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Name</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-left">Email</th><th class="text-xs text-slate-500 uppercase px-4 py-3 text-center">Role</th></tr></thead><tbody id="usersTbody" class="divide-y divide-slate-100"></tbody></table>
        </div>
    </main>

    <script>
        const users = [
            { id:1, name:'John Doe', email:'john@example.com', role:'Administrator' },
            { id:2, name:'Jane Smith', email:'jane@example.com', role:'Manager' },
            { id:3, name:'Ali Hassan', email:'ali@example.com', role:'Employee' }
        ];
        function renderUsers(filter=''){
            const tbody = document.getElementById('usersTbody');
            tbody.innerHTML = '';
            const f = filter.toLowerCase();
            users.filter(u => u.name.toLowerCase().includes(f) || u.email.toLowerCase().includes(f)).forEach(u => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td class="px-4 py-3 text-sm">${u.name}</td><td class="px-4 py-3 text-sm">${u.email}</td><td class="px-4 py-3 text-sm text-center"><span class="inline-block px-2 py-1 bg-slate-50 text-slate-700 rounded-md text-xs">${u.role}</span></td>`;
                tbody.appendChild(tr);
            });
        }
        document.getElementById('searchUser').addEventListener('input', e => renderUsers(e.target.value));
        renderUsers();
    </script>

    @include('components.modal')
    @include('components.alert')
    @include('components.confirm')
</body>
</html>
