<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleExpense;
use App\Services\ExpenseCalculatorService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(protected ExpenseCalculatorService $calculator) {}

    public function store(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'category' => ['required', 'in:engine,denting_painting,accessories,tyre,other'],
            'label' => ['nullable', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $this->calculator->addLineItem($vehicle, $validated);

        return back()->with('success', 'Expense added.');
    }

    public function destroy(Vehicle $vehicle, VehicleExpense $expense)
    {
        $this->authorize('update', $vehicle);
        abort_unless($expense->vehicle_id === $vehicle->id, 404);

        $this->calculator->removeLineItem($expense);

        return back()->with('success', 'Expense removed.');
    }
}
