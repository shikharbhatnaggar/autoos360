<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Services\SaleService;
use Illuminate\Http\Request;
use RuntimeException;

class SaleController extends Controller
{
    public function __construct(protected SaleService $saleService) {}

    public function create(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        if ($vehicle->isSold()) {
            return redirect()->route('vehicles.show', $vehicle)->with('error', 'This vehicle is already sold.');
        }

        return view('sales.create', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'purchaser_name' => ['required', 'string', 'max:150'],
            'purchaser_address' => ['nullable', 'string', 'max:255'],
            'purchaser_mobile' => ['nullable', 'string', 'max:15'],
            'reference_medium' => ['nullable', 'string', 'max:150'],
            'sale_date' => ['required', 'date'],
            'sale_rate' => ['required', 'numeric', 'min:0'],
            'commission' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            $this->saleService->recordSale($vehicle, $validated);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Sale recorded successfully.');
    }

    public function edit(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $vehicle->load('sale');

        return view('sales.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'purchaser_name' => ['required', 'string', 'max:150'],
            'purchaser_address' => ['nullable', 'string', 'max:255'],
            'purchaser_mobile' => ['nullable', 'string', 'max:15'],
            'reference_medium' => ['nullable', 'string', 'max:150'],
            'sale_date' => ['required', 'date'],
            'sale_rate' => ['required', 'numeric', 'min:0'],
            'commission' => ['nullable', 'numeric', 'min:0'],
        ]);

        $this->saleService->updateSale($vehicle, $validated);

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Sale updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $this->saleService->cancelSale($vehicle);

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Sale cancelled, vehicle back in stock.');
    }
}
