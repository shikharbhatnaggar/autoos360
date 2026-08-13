<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\VehicleController;

Route::get(
    '/tenants/{tenant_uuid}/branches/{branch_id}/vehicles',
    [VehicleController::class, 'index']
);