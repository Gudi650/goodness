<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Goodness Group — Login</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {50:'#fff8e5',100:'#fde6a1',500:'#f0b73a',600:'#eaa106',700:'#c88600'}
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

            {{-- Display error message if authentication fails --}}
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-md px-3 py-2">
                    {{-- Show the first error message --}}
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Display success message if provided --}}
            @if (session('success'))
                <div class="mb-4 text-sm text-brand-700 bg-brand-50 border border-brand-200 rounded-md px-3 py-2">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Login Form - submits POST request to /login endpoint --}}
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                {{-- CSRF Token - required by Laravel for security --}}
                {{-- Prevents cross-site request forgery attacks --}}
                @csrf

                {{-- Email Input Field --}}
                <div>
                    <label for="email" class="block text-sm text-slate-500 mb-2">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        placeholder="you@company.tz" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-md bg-white text-slate-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('email') border-red-500 @enderror" 
                        required
                    />
                    {{-- Show validation error for email field if it exists --}}
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Input Field --}}
                <div>
                    <label for="password" class="block text-sm text-slate-500 mb-2">Password</label>
                    <div class="relative">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            placeholder="••••••••" 
                            class="w-full px-4 py-2 border border-slate-300 rounded-md bg-white text-slate-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('password') border-red-500 @enderror" 
                            required
                        />
                        {{-- Show/Hide password toggle button --}}
                        <button 
                            id="togglePwd" 
                            type="button" 
                            class="absolute right-2 top-2 text-sm text-slate-500"
                        >
                            Show
                        </button>
                    </div>
                    {{-- Show validation error for password field if it exists --}}
                    @error('password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div>
                    <button 
                        type="submit" 
                        class="w-full px-4 py-2 bg-brand-600 text-white rounded-md font-medium hover:bg-brand-700 transition"
                    >
                        Sign In
                    </button>
                </div>
            </form>

            <p class="text-sm text-slate-500 mt-6 text-center">
                Need an account?
                <a href="/signup" class="text-brand-700 hover:text-brand-600 font-medium">Create one</a>
            </p>
        </div>
        <p class="text-center text-sm text-slate-500 mt-4">Goodness Group — Multi-company ERP</p>
    </div>

    <script>
        /**
         * Password visibility toggle
         * Allows user to show/hide password while typing
         */
        const toggle = document.getElementById('togglePwd');
        const pwd = document.getElementById('password');
        
        // Listen for click on the Show/Hide button
        toggle.addEventListener('click', () => {
            // If password is currently hidden, show it
            if (pwd.type === 'password') {
                pwd.type = 'text';
                toggle.textContent = 'Hide';
            } 
            // If password is currently visible, hide it
            else {
                pwd.type = 'password';
                toggle.textContent = 'Show';
            }
        });

        /**
         * Allow user to press Enter to submit the form
         * This is a common UX pattern for login forms
         */
        document.addEventListener('keydown', (e) => {
            // If user presses Enter key, submit the form
            if (e.key === 'Enter') {
                document.querySelector('form').submit();
            }
        });
    </script>
</body>
</html>
