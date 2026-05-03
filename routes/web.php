<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Companies Management
    // - GET /companies: show companies list (from DB)
    // - POST /companies: save a new company
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');

    // Users Management
    Route::get('/users', function () {
        return view('users');
    })->name('users');

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
