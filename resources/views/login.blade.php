<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Goodness Group — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {50:'#f0fdf4',100:'#dcfce7',500:'#22c55e',600:'#16a34a',700:'#15803d'}
                    },
                    fontFamily: { sans:['Inter','sans-serif'], display:['Outfit','sans-serif'] }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:Inter, sans-serif}h1,h2,nav,button{font-family:Outfit, sans-serif}.mono{font-family:ui-monospace,monospace}</style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex items-center">
    <div class="w-full max-w-md mx-auto p-6">
        <div class="bg-white border border-slate-200 shadow-sm rounded-lg p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-brand-600" aria-hidden></div>
                <div>
                    <div class="text-sm font-semibold text-slate-800">Goodness Group</div>
                    <div class="text-xs text-slate-500">Enterprise Management System</div>
                </div>
            </div>

            <h2 class="text-2xl font-semibold text-slate-800 mb-4">Sign in to your account</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-slate-500 mb-2">Email</label>
                    <input id="email" type="email" placeholder="you@company.tz" class="w-full px-4 py-2 border border-slate-300 rounded-md bg-white text-slate-800 focus:ring-2 focus:ring-green-500 focus:border-green-500" />
                </div>
                <div>
                    <label class="block text-sm text-slate-500 mb-2">Password</label>
                    <div class="relative">
                        <input id="password" type="password" placeholder="••••••••" class="w-full px-4 py-2 border border-slate-300 rounded-md bg-white text-slate-800 focus:ring-2 focus:ring-green-500 focus:border-green-500" />
                        <button id="togglePwd" type="button" class="absolute right-2 top-2 text-sm text-slate-500">Show</button>
                    </div>
                </div>
                <div>
                    <button id="signInBtn" class="w-full px-4 py-2 bg-brand-600 text-white rounded-md font-medium">Sign In</button>
                </div>
                <div id="loginWarning" class="hidden text-sm text-red-700 bg-red-50 border border-red-200 rounded-md px-3 py-2"></div>
                <div id="loginSuccess" class="hidden text-sm text-green-600">Signed in — redirecting…</div>
            </div>
        </div>
        <p class="text-center text-sm text-slate-500 mt-4">Goodness Group — Multi-company ERP</p>
    </div>

    <script>
        const toggle = document.getElementById('togglePwd');
        const pwd = document.getElementById('password');
        toggle.addEventListener('click', ()=>{
            if(pwd.type==='password'){pwd.type='text'; toggle.textContent='Hide'} else {pwd.type='password'; toggle.textContent='Show'}
        });

        document.getElementById('signInBtn').addEventListener('click', ()=>{
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const warning = document.getElementById('loginWarning');
            warning.classList.add('hidden');
            warning.textContent = '';
            if(!email || !password){
                warning.textContent = 'Please provide email and password.';
                warning.classList.remove('hidden');
                return;
            }
            document.getElementById('loginSuccess').classList.remove('hidden');
            setTimeout(()=> location.href='/dashboard', 900);
        });

        document.addEventListener('keydown', (e)=>{ if(e.key==='Enter') document.getElementById('signInBtn').click() });
    </script>
</body>
</html>
