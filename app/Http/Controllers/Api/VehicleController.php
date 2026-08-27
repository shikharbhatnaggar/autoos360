<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use App\Models\Vehicle;
use App\Models\Branch;
use App\Models\VehiclePurchase;


class VehicleController extends Controller
{
    public function index(Request $request, string $tenant_uuid, int $branch_id)
    {
        // Find tenant using UUID
        $tenant = Tenant::where('uuid', $tenant_uuid)->first();

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.',
            ], 404);
        }

        // Validate filters
        $request->validate([
            'make' => ['nullable', 'string', 'max:100'],
            'min_budget' => ['nullable', 'numeric', 'min:0'],
            'max_budget' => ['nullable', 'numeric', 'min:0'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'fuel_type' => ['nullable', 'string', 'max:50'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Vehicle::query()
            ->where('tenant_id', $tenant->id);

        $branch = null;

        if ($request->filled('branch_id')) {

            $branch = $tenant->branches()
                ->where('id', $request->branch_id)
                ->first();

            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found for this tenant.',
                ], 404);
            }

            $query->where('branch_id', $request->branch_id);
        }
        

        /*
        |--------------------------------------------------------------------------
        | Make
        |--------------------------------------------------------------------------
        */
        if ($request->filled('make')) {
            $query->where('model', 'like', '%' . $request->make . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | Fuel Type
        |--------------------------------------------------------------------------
        */
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        /*
        |--------------------------------------------------------------------------
        | Year
        |--------------------------------------------------------------------------
        */
        if ($request->filled('year')) {

            switch ($request->year) {

                case '2023_plus':
                    $query->where('make_year', '>=', 2023);
                    break;

                case '2020_2022':
                    $query->whereBetween('make_year', [2020, 2022]);
                    break;

                case '2017_2019':
                    $query->whereBetween('make_year', [2017, 2019]);
                    break;

                case 'below_2017':
                    $query->where('make_year', '<', 2017);
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Budget
        |--------------------------------------------------------------------------
        */

        if ($request->filled('budget')) {

            switch ($request->budget) {

                case 'under_5':
                    $query->whereHas('purchase', function ($q) {
                        $q->where('net_rate', '<', 500000);
                    });
                    break;

                case '5_10':
                    $query->whereHas('purchase', function ($q) {
                        $q->whereBetween('net_rate', [500000, 1000000]);
                    });
                    break;

                case '10_15':
                    $query->whereHas('purchase', function ($q) {
                        $q->whereBetween('net_rate', [1000000, 1500000]);
                    });
                    break;

                case '15_plus':
                    $query->whereHas('purchase', function ($q) {
                        $q->where('net_rate', '>=', 1500000);
                    });
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Only vehicles currently in stock
        |--------------------------------------------------------------------------
        */
        $query->where(function ($q) {
            $q->whereNull('status')
                ->orWhere('status', 'available')
                ->orWhere('status', 'in_stock');
        });

        /*
        |--------------------------------------------------------------------------
        | Latest vehicles first
        |--------------------------------------------------------------------------
        */
        $query->latest();

        $perPage = $request->input('per_page', 20);

        $vehicles = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Vehicles fetched successfully.',
            'tenant' => [
                'id' => $tenant->id,
                'uuid' => $tenant->uuid,
                'name' => $tenant->name,
            ],
            'branch' => [
                'id' => $branch?->id,
                'name' => $branch?->name,
            ],
            'filters' => [
                'make' => $request->make,
                'min_budget' => $request->min_budget,
                'max_budget' => $request->max_budget,
                'budget' => $request->budget,
                'fuel_type' => $request->fuel_type,
                'year' => $request->year,
            ],
            'data' => $vehicles->items(),
            'pagination' => [
                'current_page' => $vehicles->currentPage(),
                'last_page' => $vehicles->lastPage(),
                'per_page' => $vehicles->perPage(),
                'total' => $vehicles->total(),
            ],
        ]);
    }

    public function show(Request $request, $tenantUuid, $vehicleId)
    {
        $tenant = Tenant::where('uuid', $tenantUuid)->first();

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.',
            ], 404);
        }

        $query = Vehicle::where('id', $vehicleId)
            ->where('tenant_id', $tenant->id)
            ->where('status', 'in_stock');

        // Branch is optional — only filter by it if the caller supplied one.
        if ($request->filled('branch')) {
            $query->where('branch_id', $request->query('branch'));
        }

        $vehicle = $query->first();

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $vehicle,
        ]);
    }

    public function publicbodyTypes(Request $request, string $bodyType = null) // Default to null
    {
        $bodyTypes = Vehicle::query()
            ->selectRaw('body_type, COUNT(*) as total')
            // Only filters by body_type if the URL parameter is present
            ->when($bodyType, function ($query, $bodyType) {
                return $query->where('body_type', $bodyType);
            })
            ->groupBy('body_type')
            ->paginate(10); // Changed from ->get() to fix pagination crash

        return response()->json([
            'success' => true,
            'message' => 'bodyTypes fetched successfully.',
            'filters' => [
                'requested_body_type' => $bodyType, // Tracks if a filter was used
            ],
            'data' => $bodyTypes->items(),
            'pagination' => [
                'current_page' => $bodyTypes->currentPage(),
                'last_page' => $bodyTypes->lastPage(),
                'per_page' => $bodyTypes->perPage(),
                'total' => $bodyTypes->total(),
            ],
        ]);
    }

    public function publicbodyTypeDetails(Request $request, string $bodyType = null)
    {
        // 1. Eager-load your vehicle purchase sheets data to fix missing price payloads
        $query = Vehicle::with(['purchase'])->orderBy('created_at', 'desc');

        // 2. Clear out string parameters like "all" or "null" forwarded from JavaScript routing links
        if ($bodyType === 'all' || $bodyType === 'null' || empty($bodyType)) {
            $bodyType = null;
        }

        // 3. Strictly include the where constraint ONLY if a valid body type is requested
        if ($bodyType) {
            $query->where('body_type', $bodyType);
        }

        // Get the last 5 unique dates that contain data for this matching query scope
        $latestDates = (clone $query)
                        ->selectRaw('DATE(created_at) as date')
                        ->groupBy('date')
                        ->orderBy('date', 'desc')
                        ->take(5)
                        ->pluck('date');

        // Count records within those 5 dates for this specific scope context
        $countForLast5Dates = (clone $query)
                        ->whereIn(DB::raw('DATE(created_at)'), $latestDates)
                        ->count();

        // Determine the dynamic pool limits
        if ($countForLast5Dates < 10) {
            $limit = 10;
        } elseif ($countForLast5Dates > 50) {
            $limit = 50;
        } else {
            $limit = $countForLast5Dates;
        }

        $perPage = (int) $request->input('per_page', 20);
        
        // Apply bounds constraints to generate paginated items sets
        $vehicles = $query->take($limit)->paginate($perPage);

        // 4. Mutate output data rows to map net_rate into a unified price key attribute
        $vehicles->getCollection()->transform(function ($car) {
            $car->price = $car->purchase ? (float) $car->purchase->net_rate : 450000.00;
            return $car;
        });

        return response()->json([
            'success' => true,
            'message' => 'Vehicles fetched successfully.',
            'filters' => [
                'requested_body_type' => $bodyType, // Returns clean string or null parameter format
            ],
            'data' => $vehicles->items(),
            'pagination' => [
                'current_page' => $vehicles->currentPage(),
                'last_page'    => $vehicles->lastPage(),
                'per_page'     => $vehicles->perPage(),
                'total'        => $vehicles->total(),
            ],
        ]);
    }

    /**
     * Fetch Detailed Specific Car Payload via SEO Friendly Slug String
     */
    public function publicvehicleDetails(Request $request, string $vehicleslug)
    {
        // Extract trailing numerical id from seo string (e.g. 'hyundai-grand-i10-nios-sportz-2022-42' -> 42)
        $slugSegments = explode('-', $vehicleslug);
        $vehicleId = end($slugSegments);

        if (!is_numeric($vehicleId)) {
            return response()->json([
                'success' => false,
                'message' => 'Malformed vehicle routing identifier format.',
            ], 400);
        }

        // Eager-load the purchase record layer alongside your core inventory filters
        $vehicle = Vehicle::where('id', $vehicleId)
                    ->where('status', 'in_stock')
                    ->with(['purchase'])
                    ->first();

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle record could not be found within active stock pools.',
            ], 404);
        }

        // Inject the final retail rate parameter explicitly as 'price' for the frontend JS engines
        // Fallback default value applied if no corresponding purchase sheet row exists yet
        $vehicle->price = $vehicle->purchase ? (float) $vehicle->purchase->net_rate : 450000.00;

        return response()->json([
            'success' => true,
            'message' => 'Vehicle details fetched successfully.',
            'data' => $vehicle
        ]);
    }

    /**
     * Fetch Multi-Grid Catalog Listing Feed
     */
    public function publicvehicleListing(Request $request, ?string $section = null) 
    {
        // Build query with purchase model configurations pre-loaded immediately to avoid N+1 issues
        $query = Vehicle::with(['purchase'])->orderBy('created_at', 'desc');

        if ($section) {
            if ($section == 'featured') {
                $query->where('is_featured', 1);
            } elseif ($section == 'new_arrivals') {
                $query->where('is_new_arrival', 1);
            } elseif ($section == 'commercial') {
                $query->where('is_commercial', 1);
            }
        }

        // 1. Get the last 5 unique dates that contain data
        $latestDates = Vehicle::selectRaw('DATE(created_at) as date')
                        ->groupBy('date')
                        ->orderBy('date', 'desc')
                        ->take(5)
                        ->pluck('date');

        // 2. Count records within those 5 dates
        $countForLast5Dates = Vehicle::whereIn(DB::raw('DATE(created_at)'), $latestDates)->count();

        // 3. Determine the dynamic limit (Cap the absolute maximum pool size)
        if ($countForLast5Dates < 10) {
            $limit = 10;
        } elseif ($countForLast5Dates > 50) {
            $limit = 50;
        } else {
            $limit = $countForLast5Dates;
        }

        // 4. Apply limit to query pool and paginate safely
        $perPage = (int) $request->input('per_page', 20);
        $vehicles = $query->take($limit)->paginate($perPage);

        // 5. Mutate output rows map array to map net_rate into a unified price variable layout
        $vehicles->getCollection()->transform(function ($car) {
            $car->price = $car->purchase ? (float) $car->purchase->net_rate : 450000.00;
            return $car;
        });

        return response()->json([
            'success' => true,
            'message' => 'Vehicles fetched successfully.',
            'filters' => [
                'section' => $section,
            ],
            'data' => $vehicles->items(),
            'pagination' => [
                'current_page' => $vehicles->currentPage(),
                'last_page'    => $vehicles->lastPage(),
                'per_page'     => $vehicles->perPage(),
                'total'        => $vehicles->total(),
            ],
        ]);
    }


}
