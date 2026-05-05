<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HRM - Goodness ERP</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, nav, button { font-family: 'Outfit', sans-serif; }
        .mono { font-family: ui-monospace, monospace; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#fff8e5', 100: '#fde6a1', 500: '#f0b73a', 600: '#eaa106', 700: '#c88600' }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 text-slate-800">
    @include('components.topbar')
    @include('components.sidebar')

    <main class="ml-0 lg:ml-64 pt-20 p-6">
        <div class="bg-white border-b border-slate-200 -mx-6 px-6 mb-6">
            <div class="flex gap-8">
                <button onclick="switchTab('employees', this)" class="tab-btn active py-4 text-sm font-medium text-slate-700 border-b-2 border-brand-600 cursor-pointer">Employees</button>
                <button onclick="switchTab('attendance', this)" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Attendance</button>
                <button onclick="switchTab('leave', this)" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Leave</button>
                <button onclick="switchTab('payroll', this)" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Payroll</button>
                <button onclick="switchTab('departments', this)" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-700 cursor-pointer">Departments</button>
            </div>
        </div>

        @include('hrm.employees')
        @include('hrm.attendance')
        @include('hrm.leave')
        @include('hrm.payroll')
        @include('hrm.departments')
    </main>

    @include('hrm.modals.salary')
    @include('hrm.modals.department')
    @include('hrm.modals.employee')
    @include('hrm.modals.bulk-import')

    @include('components.modal')
    <x-alert />
    @include('components.confirm')
    @include('hrm.scripts')
</body>

</html>
