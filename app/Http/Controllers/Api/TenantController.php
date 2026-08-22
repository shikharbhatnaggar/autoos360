<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTenantRequest;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class TenantController extends Controller
{
    /**
     * Handle multi-tenant self-registration setups.
     */
    public function register(RegisterTenantRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            // 1. Generate an automated unique URL friendly company slug 
            // Example: "Apex Premium Motors" -> "apex-premium-motors"
            $baseSlug = Str::slug($validatedData['name']);
            $finalSlug = $baseSlug;
            
            // Defend against collision paths if two dealers register under matching titles
            $counter = 1;
            while (Tenant::where('slug', $finalSlug)->exists()) {
                $finalSlug = $baseSlug . '-' . $counter;
                $counter++;
            }

            // 2. Build model array data injecting essential backend traits
            $tenantData = array_merge($validatedData, [
                'uuid'      => (string) Str::uuid(),
                'slug'      => $finalSlug,
                'status'    => 'inactive', // Set inactive status profile defaults
                'timezone'  => 'Asia/Kolkata',
                'currency'  => 'INR',
            ]);

            $tenant = Tenant::create($tenantData);

            // 3. Return formatted API response blocks matching standard payload layouts
            return response()->json([
                'success' => true,
                'message' => 'Dealer tenant registered successfully.',
                'data'    => [
                    'id'      => $tenant->id,
                    'uuid'    => $tenant->uuid,
                    'name'    => $tenant->name,
                    'slug'    => $tenant->slug,
                    'email'   => $tenant->email,
                    'status'  => $tenant->status,
                ]
            ], 201); // Status Code 201: Created

        } catch (Exception $transactionError) {
            // Track anomalies inside storage tracing channels securely
            Log::error('Tenant registration system error failure: ' . $transactionError->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error occurred while handling registration profiles.',
            ], 500);
        }
    }
}
