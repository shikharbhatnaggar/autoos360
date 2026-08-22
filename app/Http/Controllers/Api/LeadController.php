<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class LeadController extends Controller
{
    /**
     * Store inbound buyer booking requests.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id'  => ['required', 'integer', 'exists:vehicles,id'],
            'name'        => ['required', 'string', 'max:255'],
            'mobile'      => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'email'       => ['nullable', 'email', 'max:255'],
            'vehicle_url' => ['required', 'url'],
            'message'     => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // Find parent car profile row to assign the lead to the correct tenant dashboard
            $vehicle = Vehicle::findOrFail($request->input('vehicle_id'));

            $lead = Lead::create([
                'tenant_id'   => $vehicle->tenant_id, // Multi-tenant route matching dependency injection
                'vehicle_id'  => $vehicle->id,
                'name'        => $request->input('name'),
                'mobile'      => $request->input('mobile'),
                'email'       => $request->input('email'),
                'vehicle_url' => $request->input('vehicle_url'),
                'message'     => $request->input('message'),
                'status'      => 'new'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test drive requested successfully! The dealer will contact you shortly.',
                'lead_id' => $lead->id
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error processing test drive request.'
            ], 500);
        }
    }
}
