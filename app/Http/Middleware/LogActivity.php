<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lightweight request-level audit trail for state-changing requests.
 * Complements the more detailed logging already done inside
 * VehicleService/SaleService for domain-specific actions.
 */
class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => strtolower($request->method()) . ':' . $request->path(),
                'model_type' => 'HttpRequest',
                'model_id' => null,
                'ip_address' => $request->ip(),
            ]);
        }

        return $response;
    }
}
