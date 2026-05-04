<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
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

    // Finance Management
    Route::get('/finance', function () {
        return view('finance');
    })->name('finance');

    // HRM Management
    Route::get('/hrm', function () {
        return view('hrm');
    })->name('hrm');

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

