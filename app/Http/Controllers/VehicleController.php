<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Branch;
use App\Models\Broker;
use App\Models\VehicleDocument;
use App\Services\VehicleService;
use App\Services\VehicleDocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function __construct(
        protected VehicleService $vehicleService,
        protected VehicleDocumentService $vehicleDocumentService
        ) {}

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

        $documents = [
            'vehicle' => [],
            // 'seller' => [],
            'buyer' => [],
        ];

        return view('vehicles.create', compact('branches', 'brokers', 'documents'));
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


            'make_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'km_driven' => ['nullable', 'integer', 'min:0'],
            'fuel_type' => ['nullable', 'string', 'max:30'],
            'transmission' => ['nullable', 'string', 'max:30'],
            'ownership' => ['nullable', 'string', 'max:30'],
            'engine_cc' => ['nullable', 'integer', 'min:0'],
            'engine_description' => ['nullable', 'string', 'max:150'],
            'engine_power_ps' => ['nullable', 'numeric', 'min:0'],
            'mileage_claimed' => ['nullable', 'numeric', 'min:0'],
            'seating_capacity' => ['nullable', 'integer', 'min:1', 'max:20'],
            'fuel_tank' => ['nullable', 'numeric', 'min:0'],
            'colour' => ['nullable', 'string', 'max:80'],
            'insurance_type' => ['nullable', 'string', 'max:80'],
            'insurance_valid_till' => ['nullable', 'date'],
            'registration_no' => ['nullable', 'string', 'max:30'],

            'inspection_highlights' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],

            'document' => ['nullable', 'array'],

            'document.*.document_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'document.*.valid_till' => [
                'nullable',
                'date',
            ],

            'document.*.file' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        
        $vehicle = $this->vehicleService->createWithPurchase(
            vehicleData: array_merge(
                collect($validated)->only([
                    'branch_id',
                    'sr_no',
                    'memo_no',
                    'vehicle_no',
                    'model',
                    'make_year',
                    'km_driven',
                    'fuel_type',
                    'transmission',
                    'ownership',
                    'engine_cc',
                    'engine_description',
                    'engine_power_ps',
                    'mileage_claimed',
                    'seating_capacity',
                    'fuel_tank',
                    'colour',
                    'insurance_type',
                    'insurance_valid_till',
                    'registration_no',
                ])->toArray(),

                [
                    'inspection_highlights' => $this->linesToArray(
                        $request->input('inspection_highlights')
                    ),

                    'features' => $this->linesToArray(
                        $request->input('features')
                    ),
                ]
            ),

            purchaseData: collect($validated)->only([
                'seller_name',
                'seller_address',
                'seller_mobile',
                'reference_type',
                'broker_id',
                'purchase_date',
                'purchase_rate',
                'commission',
            ])->toArray(),

            expenses: $validated['expenses'] ?? []
        );

        $this->saveVehicleDocuments(
            $vehicle,
            $request
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

        $vehicle->load([
            'purchase',
            'expenses',
            'documents',
        ]);

        $documents = [
            'vehicle' => $vehicle->documents
                ->where('section', 'vehicle')
                ->keyBy('document_type'),

            'seller' => $vehicle->documents
                ->where('section', 'seller')
                ->keyBy('document_type'),

            // 'buyer' => $vehicle->documents
            //     ->where('section', 'buyer')
            //     ->keyBy('document_type'),
        ];

        return view('vehicles.edit', compact('vehicle', 'brokers', 'documents'));
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

            'make_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'km_driven' => ['nullable', 'integer', 'min:0'],
            'fuel_type' => ['nullable', 'string', 'max:30'],
            'transmission' => ['nullable', 'string', 'max:30'],
            'ownership' => ['nullable', 'string', 'max:30'],
            'engine_cc' => ['nullable', 'integer', 'min:0'],
            'engine_description' => ['nullable', 'string', 'max:150'],
            'engine_power_ps' => ['nullable', 'numeric', 'min:0'],
            'mileage_claimed' => ['nullable', 'numeric', 'min:0'],
            'seating_capacity' => ['nullable', 'integer', 'min:1', 'max:20'],
            'fuel_tank' => ['nullable', 'numeric', 'min:0'],
            'colour' => ['nullable', 'string', 'max:80'],
            'insurance_type' => ['nullable', 'string', 'max:80'],
            'insurance_valid_till' => ['nullable', 'date'],
            'registration_no' => ['nullable', 'string', 'max:30'],

            'inspection_highlights' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],

            'document' => ['nullable', 'array'],

            'document.*.document_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'document.*.valid_till' => [
                'nullable',
                'date',
            ],

            'document.*.file' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $vehicle->update(
            array_merge(
                collect($validated)->only([
                    'sr_no',
                    'memo_no',
                    'vehicle_no',
                    'model',
                    'make_year',
                    'km_driven',
                    'fuel_type',
                    'transmission',
                    'ownership',
                    'engine_cc',
                    'engine_description',
                    'engine_power_ps',
                    'mileage_claimed',
                    'seating_capacity',
                    'fuel_tank',
                    'colour',
                    'insurance_type',
                    'insurance_valid_till',
                    'registration_no',
                ])->toArray(),

                [
                    'inspection_highlights' => $this->linesToArray(
                        $request->input('inspection_highlights')
                    ),

                    'features' => $this->linesToArray(
                        $request->input('features')
                    ),
                ]
            )
        );

        $this->vehicleService->updatePurchase($vehicle, collect($validated)->only([
            'seller_name', 'seller_address', 'seller_mobile', 'reference_type',
            'broker_id', 'purchase_date', 'purchase_rate', 'commission',
        ])->toArray());

        $this->vehicleService->syncExpenses($vehicle, $validated['expenses'] ?? []);

        $this->saveVehicleDocuments(
            $vehicle,
            $request
        );

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        $this->vehicleService->delete($vehicle);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted.');
    }

    private function linesToArray(?string $value): array
    {
        if (!$value) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->toArray();
    }

    private function saveVehicleDocuments(
        Vehicle $vehicle,
        Request $request
    ): void {

        foreach (VehicleDocument::VEHICLE_DOCUMENTS as $type => $name) {

            $this->vehicleDocumentService->save(
                vehicle: $vehicle,
                section: 'vehicle',
                documentType: $type,
                documentName: $name,
                documentNo: $request->input("document.vehicle.$type.document_no"),
                validTill: $request->input("document.vehicle.$type.valid_till"),
                file: $request->file("document.vehicle.$type.file"),
            );
        }


        foreach (VehicleDocument::ID_DOCUMENTS as $type => $name) {

            $this->vehicleDocumentService->save(
                vehicle: $vehicle,
                section: 'seller',
                documentType: $type,
                documentName: $name,
                documentNo: $request->input("document.seller.$type.document_no"),
                validTill: null,
                file: $request->file("document.seller.$type.file"),
            );
        }


        foreach (VehicleDocument::ID_DOCUMENTS as $type => $name) {

            $this->vehicleDocumentService->save(
                vehicle: $vehicle,
                section: 'buyer',
                documentType: $type,
                documentName: $name,
                documentNo: $request->input("document.buyer.$type.document_no"),
                validTill: null,
                file: $request->file("document.buyer.$type.file"),
            );
        }
    }
}
