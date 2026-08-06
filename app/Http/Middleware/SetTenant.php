<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TenantManager;

class SetTenant
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = auth()->user()->tenant;

        if (!$tenant) {
            abort(403, 'Tenant not assigned.');
        }

        app(TenantManager::class)->set($tenant);

        view()->share('tenant', $tenant);

        return $next($request);
    }
}