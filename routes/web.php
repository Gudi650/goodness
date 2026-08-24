<?php

use App\Http\Controllers\AssetsCategoriesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\balanceSheetController;
use App\Http\Controllers\BulkImportController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CreateAssetsController;
use App\Http\Controllers\CreateLiabilityController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DividendsController;
use App\Http\Controllers\EquityController;
use App\Http\Controllers\EquityDistributionController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FAR;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\FinanceItemsController;
use App\Http\Controllers\HatcheryMachine\CalibrationController;
use App\Http\Controllers\HatcheryMachine\IotSensorController;
use App\Http\Controllers\HatcheryMachine\MachineAlarmController;
use App\Http\Controllers\HatcheryMachine\MachineController;
use App\Http\Controllers\HatcheryMachine\MachineLogController;
use App\Http\Controllers\HatcheryMachine\MachineMaintenanceController;
use App\Http\Controllers\HatcheryMachine\MaintenanceScheduleController;
use App\Http\Controllers\HrmController;
use App\Http\Controllers\IncomeStatement;
use App\Http\Controllers\InternalMessagesController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemsCategoryController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\LiabilityCategoryController;
use App\Http\Controllers\Loans\LoanController;
use App\Http\Controllers\Loans\LoanRepaymentScheduleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SharePremuimsController;
use App\Http\Controllers\SharesDefinitionsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\TrueCashflowController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VatAccount;
use App\Http\Controllers\VirtualAccountsController;
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
    Route::resource('companies', CompanyController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names([
            'index' => 'companies',
            'store' => 'companies.store',
            'update' => 'companies.approve',
            'destroy' => 'companies.destroy',
        ]);

    Route::post('/active-company', [CompanyController::class, 'setActiveCompany'])
        ->name('active-company.store');

    // Users Management
    // - GET /users: show users list (from DB)
    // - PUT /users/{user}/role: update a user's role
    // - PUT /users/{user}/company: update a user's company
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users');
        Route::put('/users/{user}/role', 'updateRole')->name('users.updateRole');
        Route::put('/users/{user}/company', 'updateCompany')->name('users.updateCompany');
    });

    // Roles Management
    // - POST /roles: create a new role (used by the Create Role modal)
    Route::controller(RoleController::class)->group(function () {
        Route::post('/roles', 'store')->name('roles.store');
        Route::put('/roles/{role}', 'update')->name('roles.update');
        Route::delete('/roles/{role}', 'destroy')->name('roles.destroy');
    });

    // Invoice Management
    Route::controller(InvoiceController::class)->group(function () {
        Route::post('/invoices', 'store')->name('invoices.store');
        Route::post('/invoices/draft', 'saveDraft')->name('invoices.draft');
        Route::get('/invoices', 'index')->name('invoices.index');
        Route::get('/invoices/{invoice}/download', 'download')->name('invoices.download');
        Route::get('/invoices/{invoice}', 'show')->name('invoices.show');
        Route::put('/invoices/{invoice}', 'update')->name('invoices.update');
        Route::delete('/invoices/{invoice}', 'destroy')->name('invoices.destroy');
        Route::get('/finance/invoices/search', [FinanceController::class, 'searchInvoices'])->name('finance.invoices.search');
    });

    // Finance Management
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance');

    // expenses
    Route::controller(ExpensesController::class)->group(function () {
        Route::post('/expenses', 'storeExpense')->name('expenses.store');
        Route::delete('/expenses/{expense}', 'destroy')->name('expenses.destroy');
        Route::get('/expenses/{expense}/download', 'downloadAttachment')->name('expenses.download');
        Route::patch('/expenses/{expense}/approve', 'approveExpense')->name('expenses.approve');
        Route::get('/expenses/{expense}/review', 'reviewExpense')->name('expenses.review');
        Route::patch('/expenses/{expense}/review', 'storeExpenseReview')->name('expenses.review.store');
        Route::post('/expenses/{expense}/issue', 'issueExpense')->name('expenses.issue');
        Route::get('/finance/expenses/search', [FinanceController::class, 'searchExpenses'])->name('finance.expenses.search');
    });

    // Payments Management
    Route::controller(PaymentController::class)->group(function () {
        Route::post('/payments', 'store')->name('payments.store');
        Route::get('/payments/{payment}', 'show')->name('payments.show');
        Route::get('/payments/{payment}/edit', 'edit')->name('payments.edit');
        Route::put('/payments/{payment}', 'update')->name('payments.update');
        Route::delete('/payments/{payment}', 'destroy')->name('payments.destroy');
        Route::get('/payments/{payment}/download-proof', 'downloadProof')->name('payments.download-proof');
    });

    // Virtual accounts management
    Route::controller(VirtualAccountsController::class)->group(function () {
        Route::post('/accounts', 'store')->name('virtualaccounts.store');
    });

    // Assets management
    Route::controller(AssetsCategoriesController::class)->group(function () {
        Route::post('/assets/categories', 'store')->name('assets.categories.store');
    });

    //Liability managements
    Route::controller(LiabilityCategoryController::class)->group(function () {
        Route::post('/liabilities/categories', 'store')->name('liabilities.categories.store');
    });

    // Assets management
    Route::controller(CreateAssetsController::class)->group(function () {
        Route::post('/assets', 'store')->name('assets.store');
        Route::post('/assets/{asset}', 'revaluate')->name('assets.revaluate');
        Route::get('/assets/{asset}/', 'show')->name('assets.revaluate.show');
    });

    //Liability managements
    Route::controller(CreateLiabilityController::class)->group(function () {
        Route::post('/liabilities', 'store')->name('liabilities.store');
    });

    //route to store the new item category in the database
    Route::controller(ItemsCategoryController::class)->group(function () {
        Route::post('/items/categories', 'store')->name('items.categories.store');
    });

    //routes to store the new item and category in the database
    Route::controller(FinanceItemsController::class)->group(function () {
        Route::post('/items', 'store')->name('items.store');
    });

    // group the hrm management
    Route::controller(HrmController::class)->group(function () {

        Route::get('/hrm', 'index')->name('hrm');

    });

    // group the usercontroller routes for employees
    Route::controller(UserController::class)->group(function () {
        Route::post('/employees', 'store')->name('employees.store');
        Route::delete('/employees/{user}', 'destroy')->name('employees.destroy');
    });

    // group the department routes
    Route::controller(DepartmentController::class)->group(function () {
        Route::post('/departments', 'store')->name('departments.store');
        Route::put('/departments/{department}', 'update')->name('departments.update');
        Route::delete('/departments/{department}', 'destroy')->name('departments.destroy');
    });

    // group bulk import routes
    Route::controller(BulkImportController::class)->group(function () {
        Route::post('/bulk-import/preview', 'previewImport')->name('bulk-import.preview');
        Route::post('/bulk-import/confirm', 'confirmImport')->name('bulk-import.confirm');
    });


    // group payroll routes
    Route::controller(PayrollController::class)->group(function () {
        Route::get('/payroll', 'index')->name('payroll.index');
        Route::post('/payroll', 'store')->name('payroll.store');
        Route::put('/payroll/{salary}', 'update')->name('payroll.update');
        Route::delete('/payroll/{salary}', 'destroy')->name('payroll.destroy');
    });

    // group routes of account settings
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/settings', 'index')->name('settings');
        Route::put('/settings/profile', 'updateProfile')->name('settings.update.profile');
        Route::put('/settings/password', 'updatePassword')->name('settings.update.password');
        Route::put('/settings/preferences', 'updatePreferences')->name('settings.update.preferences');
    });

    // Sales Management

    Route::get('/sales', [SalesController::class, 'index'])->name('sales');

    // group the customers routes
    Route::controller(CustomerController::class)->group(function () {
        Route::post('/customers', 'store')->name('customers.store');
        Route::get('/customers/{customer}', 'show')->name('customers.show');
        Route::put('/customers/{customer}', 'update')->name('customers.update');
        Route::delete('/customers/{customer}', 'destroy')->name('customers.destroy');
    });

    // group orders routes
    Route::controller(OrderController::class)->group(function () {
        Route::post('/orders', 'store')->name('orders.store');
        Route::get('/orders/{order}', 'show')->name('orders.show');
        Route::put('/orders/{order}', 'update')->name('orders.update');
        Route::delete('/orders/{order}', 'destroy')->name('orders.destroy');
    });


    // contract group routes
    Route::controller(ContractController::class)->group(function () {
        Route::post('/contracts', 'store')->name('contracts.store');
        Route::get('/contracts/{contract}', 'show')->name('contracts.show');
        Route::put('/contracts/{contract}', 'update')->name('contracts.update');
        Route::delete('/contracts/{contract}', 'destroy')->name('contracts.destroy');
    });


    // leaves group routes
    Route::controller(LeavesController::class)->group(function () {
        Route::post('/leaves', 'store')->name('leaves.store');
        Route::get('/leaves', 'index')->name('leaves.index');
        Route::get('/leaves/{leave}', 'show')->name('leaves.show');
        Route::put('/leaves/{leave}', 'update')->name('leaves.update');
        Route::delete('/leaves/{leave}', 'destroy')->name('leaves.destroy');
    });

    // Inventory Management
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');

    // group the products routes
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('products.index');
        Route::get('/products/{product}', 'show')->name('products.show');
        Route::post('/products', 'store')->name('products.store');
        Route::put('/products/{product}', 'update')->name('products.update');
        Route::delete('/products/{product}', 'destroy')->name('products.destroy');
    });


    // group the suppliers routes
    Route::controller(SupplierController::class)->group(function () {
        Route::post('/suppliers', 'store')->name('suppliers.store');
        Route::put('/suppliers/{supplier}', 'update')->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', 'destroy')->name('suppliers.destroy');
    });

    // download  attachements list
    Route::get('/suppliers/{supplier}/download/{type}', [SupplierController::class, 'downloadAttachment'])->name('suppliers.downloadAttachment');


    // group purchase orders routes
    Route::controller(PurchaseOrderController::class)->group(function () {
        Route::get('/purchase-orders/{purchaseOrder}', 'show')->name('purchase-orders.show');
        Route::post('/purchase-orders', 'store')->name('purchase-orders.store');
        Route::put('/purchase-orders/{purchaseOrder}', 'update')->name('purchase-orders.update');
        Route::delete('/purchase-orders/{purchaseOrder}', 'destroy')->name('purchase-orders.destroy');
        Route::get('/purchase-orders/{purchaseOrder}/download', 'downloadAttachment')->name('purchase-orders.download');
    });

    //routes for Equity
    Route::controller(EquityController::class)->group(function () {
        Route::get('/equity', 'index')->name('equity');
    });

    //route to equity distribution controller
    Route::controller(EquityDistributionController::class)->group(function () {
        Route::post('/equity-distributions', 'store')->name('equity-distributions.store');
    });

    //routes to shares Definition 
    Route::controller(SharesDefinitionsController::class)->group(function () {
        Route::post('/shares-definitions', 'store')->name('shares-definitions.store');
        Route::put('/shares-definitions/{id}', 'update')->name('shares-definitions.update');
    });

    //routes to dividends controller
    Route::controller(DividendsController::class)->group(function () {
        Route::post('/dividends', 'store')->name('dividends.store');
        
    });

    //route to store the sharePremiums controller
    Route::controller(SharePremuimsController::class)->group(function () {
        Route::post('/share-premiums', 'store')->name('share-premiums.store');
    });

    //FAR management
    Route::get('/far', [FAR::class, 'index'])->name('far');

    // Dedicated financial statement previews and exports
    Route::get('/balance-sheet', [BalanceSheetController::class, 'index'])->name('balance-sheet');
    Route::get('/balance_sheet', [BalanceSheetController::class, 'exportPdf'])->name('balance_sheet');
    Route::get('/income-statement', [IncomeStatement::class, 'index'])->name('income-statement');
    Route::get('/income-statement-export', [IncomeStatement::class, 'exportPdf'])->name('income-statement-export');
    Route::get('/trial-balance', [TrialBalanceController::class, 'index'])->name('trial-balance');
    Route::get('/trial-balance-export', [TrialBalanceController::class, 'exportPdf'])->name('trial-balance-export');
    Route::get('/cash-flow', [TrueCashflowController::class, 'previewPdf'])->name('cash-flow');
    Route::get('/cash-flow-export', [TrueCashflowController::class, 'downloadPdf'])->name('cash-flow-export');
    Route::get('/equity-statement', [CashFlowController::class, 'previewPdf'])->name('equity-statement');
    Route::get('/equity-statement-export', [CashFlowController::class, 'downloadPdf'])->name('equity-statement-export');


    //Routes for hatcheering an prouction processes
    Route::get('/production', function () {
        return view('production');
    })->name('production');

    //Routes for machine maintenance and monitoring
    Route::prefix('machine')->group(function () {

        // Machine Maintenance View
        Route::get('/', [MachineMaintenanceController::class, 'index'])->name('machine');

        // Core Machine Operations
        Route::controller(MachineController::class)->group(function () {
            Route::post('/', 'store')->name('machines.store');
            Route::put('/{machine}', 'update')->name('machines.update');
            Route::delete('/{machine}', 'destroy')->name('machines.destroy');
        });

        // Sub-resources
        Route::post('/logs', [MachineLogController::class, 'store'])->name('logs.store');

        Route::controller(MachineAlarmController::class)->prefix('alarms')->name('alarms.')->group(function () {
            Route::post('/', 'store')->name('store');
            Route::patch('/{alarm}/resolve', 'resolve')->name('resolve');
        });

        Route::controller(MaintenanceScheduleController::class)->prefix('schedule')->name('schedule.')->group(function () {
            Route::post('/', 'store')->name('store');
            Route::patch('/{schedule}/complete', 'complete')->name('complete');
        });

        Route::post('/calibration', [CalibrationController::class, 'store'])->name('calibration.store');
        Route::post('/sensors', [IotSensorController::class, 'store'])->name('sensors.store');
        
    });

    /*
|--------------------------------------------------------------------------
| Loans Module Routes
|--------------------------------------------------------------------------
| Include this file from routes/web.php:
|   require __DIR__.'/loans.php';
| (nest the require call inside your existing Route::middleware('auth')
| ->group() so these sit behind login like your other modules)
*/

/*
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/', [LoanController::class, 'index'])->name('index');

        // Loan register
        Route::post('/', [LoanController::class, 'store'])->name('store');
        Route::put('/{loan}', [LoanController::class, 'update'])->name('update');
        Route::delete('/{loan}', [LoanController::class, 'destroy'])->name('destroy');
        
        // Repayment schedule
        Route::post('/{loan}/schedule/regenerate', [LoanRepaymentScheduleController::class, 'regenerate'])->name('schedule.regenerate');
        Route::patch('/schedule/{schedule}/mark-paid', [LoanRepaymentScheduleController::class, 'markPaid'])->name('schedule.mark-paid');

        Route::post('/{loan}/disburse', [LoanController::class, 'disburse'])->name('disburse');

    });
    */

    //Routes for loans
    Route::get('/loans', [LoanController::class, 'index'])->name('loans');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::put('/loans/{loan}', [LoanController::class, 'update'])->name('loans.update');
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');
    Route::post('/loans/{loan}/schedule/regenerate', [LoanRepaymentScheduleController::class, 'regenerate'])->name('loans.schedule.regenerate');
    Route::patch('/loans/schedule/{schedule}/mark-paid', [LoanRepaymentScheduleController::class, 'markPaid'])->name('loans.schedule.mark-paid');
    Route::post('/loans/{loan}/disburse', [LoanController::class, 'disburse'])->name('loans.disburse');


    //VAT Accounting
    Route::get('/vataccount', [VatAccount::class, 'index'])->name('vataccount');


    // Reports & Analytics
    Route::post('/reports', [ReportController::class, 'expenses'])->name('reports');
    Route::get('/reports', [ReportController::class, 'expenses'])->name('reports-get');

    // communication page - show messages via controller so view has data
    Route::get('/communication', [InternalMessagesController::class, 'index'])->name('communication');

    // Internal Messages Store
    Route::post('/messages/store/{threadId}', [InternalMessagesController::class, 'store'])
        ->name('messages.store');

    // function to show the individuals to message
    Route::get('/messages', [InternalMessagesController::class, 'index'])
        ->name('messages.index');

    // Internal Messages Thread
    Route::get('/messages/thread/{threadId}', [InternalMessagesController::class, 'thread'])
        ->name('messages.thread');

    // AJAX poll endpoint for the active thread
    Route::get('/messages/thread/{threadId}/poll', [InternalMessagesController::class, 'poll'])
        ->name('messages.thread.poll');

    // AJAX poll endpoint for the conversation list summary
    Route::get('/messages/conversations/poll', [InternalMessagesController::class, 'pollConversations'])
        ->name('messages.conversations.poll');

});
