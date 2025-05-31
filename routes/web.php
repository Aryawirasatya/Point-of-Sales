<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Product & Category Management
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

/*
|--------------------------------------------------------------------------
| Admin Routes (role = admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    // Dashboard
    Route::get('dashboard', [AdminController::class, 'index'])
         ->name('dashboard');

    // Register Kasir
    Route::get('register-cashier', [AdminController::class, 'showRegisterForm'])
         ->name('register.cashier');
    Route::post('register-cashier', [AdminController::class, 'registerCashier']);

    // User Management
    Route::resource('users', UserController::class)
         ->except(['show','create']);

    /*
    |--------------------------------------------------------------------------
    | Report Routes
    |--------------------------------------------------------------------------
    */

    // Landing page for all reports
    Route::get('reports', [ReportController::class, 'index'])
         ->name('reports.index');

    // HTML views
    Route::get('reports/products',      [ReportController::class, 'productReport'])
         ->name('reports.products');
    Route::get('reports/sales',         [ReportController::class, 'salesReport'])
         ->name('reports.sales');
    Route::get('reports/stock-changes', [ReportController::class, 'stockChanges'])
         ->name('reports.stock_changes');

    // Excel exports
    Route::get('reports/products/excel',      [ReportController::class, 'productsExcel'])
         ->name('reports.products.excel');
    Route::get('reports/sales/excel',         [ReportController::class, 'salesExcel'])
         ->name('reports.sales.excel');
    Route::get('reports/stock-changes/excel', [ReportController::class, 'stockChangesExcel'])
         ->name('reports.stock_changes.excel');

    // PDF exports (optional)
    Route::get('reports/products/pdf',      [ReportController::class, 'productsPdf'])
         ->name('reports.products.pdf');
    Route::get('reports/sales/pdf',         [ReportController::class, 'salesPdf'])
         ->name('reports.sales.pdf');
    Route::get('reports/stock-changes/pdf', [ReportController::class, 'stockChangesPdf'])
         ->name('reports.stock_changes.pdf');

      Route::get('reports/summary-sales', [ReportController::class, 'summarySales'])
             ->name('admin.reports.summary_sales');

        // 3) Export Excel untuk Summary Sales
        Route::get('reports/summary-sales/export-excel', [ReportController::class, 'summarySalesExportExcel'])
             ->name('admin.reports.summary_sales.export_excel');
     Route::get('reports/summary-sales/export-pdf', [ReportController::class, 'summarySalesExportPdf'])
              ->name('admin.reports.summary_sales.export_pdf');
});

/*
|--------------------------------------------------------------------------
| Cashier Routes (role = cashier)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:cashier'])
     ->prefix('cashier')
     ->name('cashier.')
     ->group(function () {

    // Dashboard
    Route::get('dashboard', [CashierController::class, 'dashboard'])
         ->name('dashboard');

    // Transactions
    Route::get('transactions',                    [TransactionController::class, 'index'])
         ->name('transactions.index');
    Route::get('transactions/create',             [TransactionController::class, 'create'])
         ->name('transactions.create');
    Route::post('transactions',                   [TransactionController::class, 'store'])
         ->name('transactions.store');
    Route::get('transactions/{sale}/invoice',     [TransactionController::class, 'invoice'])
         ->name('transactions.show');
});
