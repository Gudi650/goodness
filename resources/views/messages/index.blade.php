<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Communication - Goodness Group</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff8e5',
                            100: '#fde6a1',
                            500: '#f0b73a',
                            600: '#eaa106',
                            700: '#c88600'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, sans-serif }
        h1, h2, h3, nav, button, .font-display { font-family: Outfit, sans-serif }
        .mono { font-family: ui-monospace, SFMono-Regular, monospace }
        .scrollbar-hide::-webkit-scrollbar { display: none }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-16 lg:pt-20 px-4 lg:p-6 min-h-screen bg-slate-50">

        @include('messages.tabs')

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
            <section class="xl:col-span-3">
                <div id="internalModePane" data-mode-pane="internal" class="bg-white">
                    <div class="border-b border-slate-200 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h2 id="listTitle" class="text-base font-semibold text-slate-900">Internal Conversations</h2>
                                <p id="listSubtitle" class="text-sm text-slate-500">Departments and employees</p>
                            </div>
                            <span id="listCount" class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">12</span>
                        </div>

                        <div class="mt-4 flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-slate-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.65 5.65a7.5 7.5 0 0 0 10.6 10.6Z" />
                            </svg>
                            <input type="text" value="" placeholder="Search conversations" class="w-full border-0 bg-transparent p-0 text-sm text-slate-600 outline-none placeholder:text-slate-400">
                        </div>
                    </div>

                    <div id="conversationList" class="max-h-[calc(100vh-16rem)] overflow-y-auto scrollbar-hide p-2 bg-white">
                        @include('messages.internalmessages')
                    </div>
                </div>

                <div id="smsModePane" data-mode-pane="sms" class="hidden bg-white">
                    <div class="border-b border-slate-200 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">SMS Threads</h2>
                                <p class="text-sm text-slate-500">External phone conversations</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">8</span>
                        </div>

                        <div class="mt-4 flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-slate-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.65 5.65a7.5 7.5 0 0 0 10.6 10.6Z" />
                            </svg>
                            <input type="text" value="" placeholder="Search threads" class="w-full border-0 bg-transparent p-0 text-sm text-slate-600 outline-none placeholder:text-slate-400">
                        </div>
                    </div>

                    <div class="max-h-[calc(100vh-16rem)] overflow-y-auto scrollbar-hide p-2 bg-white">
                        @include('messages.smsmessages')
                    </div>
                </div>
            </section>

            <section class="xl:col-span-9">
                <div class="bg-white">
                    
                    @include('messages.thread')
                    
                </div>
            </section>

        </div>
    </main>

    @includeIf('messages.scripts')
</body>

</html>