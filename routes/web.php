<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BrokerController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\TenantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 🌟 Public Frontend Web Portal Routes (SSR)
|--------------------------------------------------------------------------
*/
// This now serves your public landing page completely unobstructed
Route::get('/', [HomeController::class, 'index'])->name('frontend.home');
Route::get('/listing/{view?}', [HomeController::class, 'listing'])->name('frontend.vehicles.listing');
Route::get('/vehicle/{slug}-{id}', [HomeController::class, 'showSingle'])->name('frontend.vehicles.show')->where('slug', '.*')->where('id', '[0-9]+');
// Test Drive Process Pipeline Mappings
Route::get('/book-test-drive', [HomeController::class, 'bookTestDrive'])->name('frontend.vehicles.test-drive');
Route::post('/book-test-drive', [HomeController::class, 'submitTestDrive'])->name('frontend.vehicles.test-drive.submit');
// Unified Server-Side Rendered Partner Portal Pipelines
Route::get('/become-partner', [TenantController::class, 'index'])->name('frontend.partner.register');
Route::post('/become-partner', [TenantController::class, 'store'])->name('frontend.partner.store');
// Secure Local Location Proxy Endpoint (Prevents API key exposure on client side)
Route::get('/api/locations/states/{stateCode}/cities', [TenantController::class, 'getCities'])->name('frontend.partner.api.cities');
Route::get('/showroom-scan/{slug}-{id}', [HomeController::class, 'vehicleInShowroom'])
                                        ->name('frontend.showroom.scan')
                                        ->where('slug', '.*')        // Allows any alphanumeric characters and dashes
                                        ->where('id', '[0-9]+');     // Restricts the final parameter to integers only
                                        
/*
|--------------------------------------------------------------------------
| Guest Authentication Gateways
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});


/*
|--------------------------------------------------------------------------
| 🔒 Authenticated Multi-Tenant CRM Dashboard Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    
    // Account Settings (password updates)
    Route::get('/account/password', [AccountController::class, 'editPassword'])->name('account.password.edit');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');

    // 🌟 FIXED: Moved the inner CRM landing page route to an explicit '/dashboard' path 
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Back-office Inventory Management Resource Endpoints
    Route::resource('vehicles', VehicleController::class)->middleware('branch.access');

    // Sale leg (purchaser side) — nested under a vehicle
    Route::get('vehicles/{vehicle}/sale/create', [SaleController::class, 'create'])->name('vehicles.sale.create')->middleware('branch.access');
    Route::post('vehicles/{vehicle}/sale', [SaleController::class, 'store'])->name('vehicles.sale.store')->middleware('branch.access');
    Route::get('vehicles/{vehicle}/sale/edit', [SaleController::class, 'edit'])->name('vehicles.sale.edit')->middleware('branch.access');
    Route::put('vehicles/{vehicle}/sale', [SaleController::class, 'update'])->name('vehicles.sale.update')->middleware('branch.access');
    Route::delete('vehicles/{vehicle}/sale', [SaleController::class, 'destroy'])->name('vehicles.sale.destroy')->middleware('branch.access');

    // Expense line items — nested under a vehicle
    Route::post('vehicles/{vehicle}/expenses', [ExpenseController::class, 'store'])->name('vehicles.expenses.store')->middleware('branch.access');
    Route::delete('vehicles/{vehicle}/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('vehicles.expenses.destroy')->middleware('branch.access');

    // Brokers Management (admin + branch_manager)
    Route::middleware('role:admin,branch_manager')->group(function () {
        Route::resource('brokers', BrokerController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });

    // Branches Configuration Grid (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('branches', BranchController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    });

    // Financial Reports & Analytics Ledgers
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('profit-loss', [ReportController::class, 'profitLoss'])->name('profit_loss');
        Route::get('stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('broker-commissions', [ReportController::class, 'brokerCommissions'])->name('broker_commissions')->middleware('role:admin,branch_manager');
    });

    Route::get('/leads', [DashboardController::class, 'leadsIndex'])->name('leads.index');
    Route::patch('/leads/{lead}/acknowledge', [DashboardController::class, 'acknowledgeLead'])->name('leads.acknowledge');
    
});
