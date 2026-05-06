<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HrmController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\BulkImportController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect('/login');
});

// ============================================
// Authentication Routes - Public Routes (no auth required)
// ============================================

// Display login form
Route::get('/login', function () {
    return view('login');
})->middleware('guest')->name('login');

// Handle login form submission
// This route receives the POST request from the login form
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login.submit');

// Display signup form
Route::get('/signup', function () {
    return view('signup');
})->middleware('guest')->name('signup');

// Handle signup form submission
// This route creates a new user account
Route::post('/signup', [AuthController::class, 'register'])
    ->middleware('guest')
    ->name('signup.submit');

// ============================================
// Protected Routes - Require Authentication
// ============================================

// Handle logout request
// This route logs out the user and destroys their session
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Group all protected routes with auth middleware
// All routes in this group require the user to be logged in
Route::middleware('auth')->group(function () {
    
    // Main Dashboard - First page users see after login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Companies Management
    // - GET /companies: show companies list (from DB)
    // - POST /companies: save a new company
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    Route::post('/active-company', [CompanyController::class, 'setActiveCompany'])->name('active-company.store');

    // Users Management
    // - GET /users: show users list (from DB)
    // - PUT /users/{user}/role: update a user's role
    // - PUT /users/{user}/company: update a user's company
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::put('/users/{user}/company', [UserController::class, 'updateCompany'])->name('users.updateCompany');

    // Roles Management
    // - POST /roles: create a new role (used by the Create Role modal)
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Invoice Management
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::post('/invoices/draft', [InvoiceController::class, 'saveDraft'])->name('invoices.draft');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    /* Finance Management
    Route::get('/finance', function () {
        $invoices = [
            [
                'id' => 'INV-001',
                'company' => 'Goodness Tanzania Ltd',
                'amount' => 1250000,
                'status' => 'Paid',
            ],
            [
                'id' => 'INV-002',
                'company' => 'Goodness Kenya Ltd',
                'amount' => 850000,
                'status' => 'Unpaid',
            ],
        ];

        $expenses = [];
        $payments = [];

        return view('finance', compact('invoices', 'expenses', 'payments'));
    })->name('finance'); */

    //Finance Management
    Route::get('/finance', [InvoiceController::class, 'index'])->name('finance');

    // HRM Management
    Route::get('/hrm', [HrmController::class, 'index'])->name('hrm');
    Route::post('/employees', [UserController::class, 'store'])->name('employees.store');
    Route::delete('/employees/{user}', [UserController::class, 'destroy'])->name('employees.destroy');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    // Bulk Import
    Route::post('/bulk-import/preview', [BulkImportController::class, 'previewImport'])->name('bulk-import.preview');
    Route::post('/bulk-import/confirm', [BulkImportController::class, 'confirmImport'])->name('bulk-import.confirm');

    // Payroll - minimal: record salaries
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::put('/payroll/{salary}', [PayrollController::class, 'update'])->name('payroll.update');
    Route::delete('/payroll/{salary}', [PayrollController::class, 'destroy'])->name('payroll.destroy');

    // Account Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update.password');
    Route::put('/settings/preferences', [SettingsController::class, 'updatePreferences'])->name('settings.update.preferences');

    // Sales Management
    Route::get('/sales', function () {
        return view('sales');
    })->name('sales');

    // Inventory Management
    Route::get('/inventory', function () {
        return view('inventory');
    })->name('inventory');

    // Reports & Analytics
    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');

});

