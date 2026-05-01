<?php

use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

// Main Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Companies Management
Route::get('/companies', function () {
    return view('companies');
})->name('companies');

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
