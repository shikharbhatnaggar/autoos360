<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\VehicleController;

Route::get('/test-api', function () {
    return response()->json([
        'success' => true,
        'message' => 'Laravel API route is working',
    ]);
});

Route::get(
    '/tenants/{tenant_uuid}/branches/{branch_id}/vehicles',
    [VehicleController::class, 'index']
);
