<?php
use App\Services\TenantManager;

if (!function_exists('tenant')) {

    function tenant()
    {
        return app(TenantManager::class)->get();
    }
}

if (!function_exists('tenant_id')) {

    function tenant_id()
    {
        return app(TenantManager::class)->id();
    }
}