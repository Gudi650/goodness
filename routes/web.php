<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HrmController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\BulkImportController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SalesController;
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


    //Finance Management
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance');

    //expenses
    Route::post('/expenses', [ExpensesController::class, 'storeExpense'])->name('expenses.store');
    Route::delete('/expenses/{expense}', [ExpensesController::class, 'destroy'])->name('expenses.destroy');
    Route::get('/expenses/{expense}/download', [ExpensesController::class, 'downloadAttachment'])->name('expenses.download');

    // Payments Management
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('/payments/{payment}/download-proof', [PaymentController::class, 'downloadProof'])->name('payments.download-proof');


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

    Route::get('/sales', [SalesController::class, 'index'])->name('sales');

    // Customers (sales)
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Orders (sales)
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Contracts (sales)
    Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');

    //Inventory Management
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');

    // Products (inventory)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Suppliers (inventory)
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    //download  attachements list
    Route::get('/suppliers/{supplier}/download/{type}', [SupplierController::class, 'downloadAttachment'])->name('suppliers.downloadAttachment');

    // Purchase Orders (inventory)
    Route::get('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::put('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
    Route::delete('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');
    Route::get('/purchase-orders/{purchaseOrder}/download', [PurchaseOrderController::class, 'downloadAttachment'])->name('purchase-orders.download');

    // Reports & Analytics
    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');

});

