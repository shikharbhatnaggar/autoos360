<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Branch;
use App\Models\Broker;
use App\Services\VehicleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function __construct(protected VehicleService $vehicleService) {}

    public function index(Request $request)
    {
        $user = Auth::user();

        $vehicles = Vehicle::forUser($user)
            ->with(['branch', 'purchase', 'sale'])
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->branch_id, fn ($q, $branchId) => $q->where('branch_id', $branchId))
            ->when($request->search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('vehicle_no', 'like', "%{$search}%")
                        ->orWhere('sr_no', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $branches = $user->isAdmin() ? Branch::all() : Branch::where('id', $user->branch_id)->get();

        return view('vehicles.index', compact('vehicles', 'branches'));
    }

    public function create()
    {
        $this->authorize('create', Vehicle::class);
    
        $branches = Auth::user()->isAdmin() ? Branch::all() : Branch::where('id', Auth::id())->get();
        $brokers = Broker::orderBy('name')->get();

        return view('vehicles.create', compact('branches', 'brokers'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Vehicle::class);

        $validated = $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
            'sr_no' => ['required', 'string', 'max:50'],
            'memo_no' => ['nullable', 'string', 'max:50'],
            'vehicle_no' => ['required', 'string', 'max:20'],
            'model' => ['required', 'string', 'max:150'],

            'seller_name' => ['required', 'string', 'max:150'],
            'seller_address' => ['nullable', 'string', 'max:255'],
            'seller_mobile' => ['nullable', 'string', 'max:15'],
            'reference_type' => ['required', 'in:direct,broker'],
            'broker_id' => ['nullable', 'exists:brokers,id', 'required_if:reference_type,broker'],
            'purchase_date' => ['required', 'date'],
            'purchase_rate' => ['required', 'numeric', 'min:0'],
            'commission' => ['nullable', 'numeric', 'min:0'],

            'expenses' => ['nullable', 'array'],
            'expenses.*.category' => ['required_with:expenses', 'in:engine,denting_painting,accessories,tyre,other'],
            'expenses.*.amount' => ['required_with:expenses', 'numeric', 'min:0'],
            'expenses.*.percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $vehicle = $this->vehicleService->createWithPurchase(
            vehicleData: collect($validated)->only(['branch_id', 'sr_no', 'memo_no', 'vehicle_no', 'model'])->toArray(),
            purchaseData: collect($validated)->only([
                'seller_name', 'seller_address', 'seller_mobile', 'reference_type',
                'broker_id', 'purchase_date', 'purchase_rate', 'commission',
            ])->toArray(),
            expenses: $validated['expenses'] ?? []
        );

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Vehicle purchase recorded.');
    }

    public function show(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        $vehicle->load(['branch', 'purchase.broker', 'expenses', 'sale', 'creator']);

        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $vehicle->load(['purchase', 'expenses']);
        $brokers = Broker::orderBy('name')->get();

        return view('vehicles.edit', compact('vehicle', 'brokers'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'sr_no' => ['required', 'string', 'max:50'],
            'memo_no' => ['nullable', 'string', 'max:50'],
            'vehicle_no' => ['required', 'string', 'max:20'],
            'model' => ['required', 'string', 'max:150'],
            'seller_name' => ['required', 'string', 'max:150'],
            'seller_address' => ['nullable', 'string', 'max:255'],
            'seller_mobile' => ['nullable', 'string', 'max:15'],
            'reference_type' => ['required', 'in:direct,broker'],
            'broker_id' => ['nullable', 'exists:brokers,id', 'required_if:reference_type,broker'],
            'purchase_date' => ['required', 'date'],
            'purchase_rate' => ['required', 'numeric', 'min:0'],
            'commission' => ['nullable', 'numeric', 'min:0'],

            'expenses' => ['nullable', 'array'],
            'expenses.*.category' => ['required_with:expenses', 'in:engine,denting_painting,accessories,tyre,other'],
            'expenses.*.amount' => ['required_with:expenses', 'numeric', 'min:0'],
            'expenses.*.percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $vehicle->update(collect($validated)->only(['sr_no', 'memo_no', 'vehicle_no', 'model'])->toArray());

        $this->vehicleService->updatePurchase($vehicle, collect($validated)->only([
            'seller_name', 'seller_address', 'seller_mobile', 'reference_type',
            'broker_id', 'purchase_date', 'purchase_rate', 'commission',
        ])->toArray());

        $this->vehicleService->syncExpenses($vehicle, $validated['expenses'] ?? []);

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        $this->vehicleService->delete($vehicle);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted.');
    }
}
