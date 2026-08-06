<?php

namespace App\Http\Middleware;

use App\Models\Vehicle;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Scopes access to route-model-bound Vehicles (and similar branch-owned
 * models) to the current user's branch. Admins bypass this check.
 * Attach to any route with a {vehicle} (or similar) route parameter.
 */
class EnsureBranchAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        $vehicle = $request->route('vehicle');

        if ($vehicle instanceof Vehicle && $vehicle->branch_id !== $user->branch_id) {
            abort(403, 'You do not have access to this branch\'s records.');
        }

        return $next($request);
    }
}
