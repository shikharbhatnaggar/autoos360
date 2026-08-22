<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\LeadController;

Route::get(
    '/tenants/{tenant_uuid}/branches/{branch_id}/vehicles',
    [VehicleController::class, 'index']
);

Route::get(
    '/tenants/{tenantUuid}/vehicles/{vehicle}',
    [VehicleController::class, 'show']
);

// To get the bodytypes list and details of a specific bodytype, we can use the following routes:
Route::get(
    '/bodytypes/{bodytype?}', // Added '?' to make it optional
    [VehicleController::class, 'publicbodyTypes']
);

// To get the vehicles list and details of a specific body type, we can use the following routes:
Route::get(
    '/bodytype/{bodytype?}', // Added '?' to make it optional
    [VehicleController::class, 'publicbodyTypeDetails']
);

// To get the vehicles list and details of a specific section, we can use the following routes:
Route::get('/vehicles/{section?}', 
    [VehicleController::class, 'publicvehicleListing']
);

// To get the details of a specific vehicle, we can use the following route:
Route::get(
    '/vehicle/{vehicle}',
    [VehicleController::class, 'publicvehicleDetails']
);

// Public registration endpoint channel
Route::post('/register-partner', [TenantController::class, 'register']);

// Public test drive booking endpoint channel
Route::post('/book-test-drive', [LeadController::class, 'store']);
