<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}
