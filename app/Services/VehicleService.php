<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\VehiclePurchase;
use App\Models\VehicleExpense;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VehicleService
{
    public function __construct(
        protected ExpenseCalculatorService $expenseCalculator
    ) {}

    /**
     * Create a vehicle together with its purchase (seller) record and
     * any initial expense line items — mirrors the top half of the memo.
     */
    public function createWithPurchase(array $vehicleData, array $purchaseData, array $expenses = []): Vehicle
    {
        return DB::transaction(function () use ($vehicleData, $purchaseData, $expenses) {
            $vehicle = Vehicle::create([
                ...$vehicleData,
                'status' => 'in_stock',
                'created_by' => Auth::id(),
            ]);

            foreach ($expenses as $expense) {
                $vehicle->expenses()->create($expense);
            }

            $expensesTotal = $this->expenseCalculator->totalFor($vehicle);

            $purchase = $vehicle->purchase()->create([
                ...$purchaseData,
                'expenses_total' => $expensesTotal,
            ]);

            $purchase->update(['net_rate' => $purchase->calculateNetRate()]);

            $this->log('created', $vehicle);

            return $vehicle->fresh(['purchase', 'expenses', 'branch']);
        });
    }

    /**
     * Update the purchase leg and recompute net rate whenever rate,
     * commission, or expenses change.
     */
    public function updatePurchase(Vehicle $vehicle, array $purchaseData): VehiclePurchase
    {
        return DB::transaction(function () use ($vehicle, $purchaseData) {
            $purchase = $vehicle->purchase;
            $purchase->update($purchaseData);

            $purchase->expenses_total = $this->expenseCalculator->totalFor($vehicle);
            $purchase->net_rate = $purchase->calculateNetRate();
            $purchase->save();

            // If the vehicle is already sold, purchase changes affect profit/loss — recompute.
            if ($vehicle->isSold()) {
                app(SaleService::class)->recomputeProfitLoss($vehicle);
            }

            $this->log('updated_purchase', $vehicle);

            return $purchase;
        });
    }

    public function delete(Vehicle $vehicle): void
    {
        DB::transaction(function () use ($vehicle) {
            $this->log('deleted', $vehicle);
            $vehicle->delete(); // soft delete
        });
    }

    protected function log(string $action, Vehicle $vehicle): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => Vehicle::class,
            'model_id' => $vehicle->id,
            'ip_address' => request()?->ip(),
        ]);
    }

    // While update
    public function syncExpenses(Vehicle $vehicle, array $expenses): void
    {
        DB::transaction(function () use ($vehicle, $expenses) {
            $vehicle->expenses()->delete();

            foreach ($expenses as $expense) {
                $vehicle->expenses()->create([
                    'category' => $expense['category'],
                    'amount' => $expense['amount'],
                    'percentage' => $expense['percentage'] ?? null,
                ]);
            }

            // if purchase->expenses_total / net_rate are stored (not computed), recalc here too
            $vehicle->purchase()->update([
                'expenses_total' => $vehicle->expenses()->sum('amount'),
            ]);
        });
    }
}
