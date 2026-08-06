<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BrokerController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

// ---- Guest routes ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// ---- Authenticated routes ----
// Route::middleware('auth')->group(function () {
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    
    // Account (password change)
    Route::get('/account/password', [AccountController::class, 'editPassword'])
        ->name('account.password.edit');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])
        ->name('account.password.update');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Vehicles (purchase leg lives here too — sr_no/model/seller fields on create/store)
    Route::resource('vehicles', VehicleController::class)
        ->middleware('branch.access');

    // Sale leg (purchaser side) — nested under a vehicle
    Route::get('vehicles/{vehicle}/sale/create', [SaleController::class, 'create'])
        ->name('vehicles.sale.create')->middleware('branch.access');
    Route::post('vehicles/{vehicle}/sale', [SaleController::class, 'store'])
        ->name('vehicles.sale.store')->middleware('branch.access');
    Route::get('vehicles/{vehicle}/sale/edit', [SaleController::class, 'edit'])
        ->name('vehicles.sale.edit')->middleware('branch.access');
    Route::put('vehicles/{vehicle}/sale', [SaleController::class, 'update'])
        ->name('vehicles.sale.update')->middleware('branch.access');
    Route::delete('vehicles/{vehicle}/sale', [SaleController::class, 'destroy'])
        ->name('vehicles.sale.destroy')->middleware('branch.access');

    // Expense line items — nested under a vehicle
    Route::post('vehicles/{vehicle}/expenses', [ExpenseController::class, 'store'])
        ->name('vehicles.expenses.store')->middleware('branch.access');
    Route::delete('vehicles/{vehicle}/expenses/{expense}', [ExpenseController::class, 'destroy'])
        ->name('vehicles.expenses.destroy')->middleware('branch.access');

    // Brokers (admin + branch_manager)
    Route::middleware('role:admin,branch_manager')->group(function () {
        Route::resource('brokers', BrokerController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });

    // Branches (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('branches', BranchController::class)->only(['index', 'create', 'store', 'edit', 'update', 'update']);
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('profit-loss', [ReportController::class, 'profitLoss'])->name('profit_loss');
        Route::get('stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('broker-commissions', [ReportController::class, 'brokerCommissions'])
            ->name('broker_commissions')->middleware('role:admin,branch_manager');
    });
});
