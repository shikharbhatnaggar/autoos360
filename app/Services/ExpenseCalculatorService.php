<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\VehicleExpense;

class ExpenseCalculatorService
{
    /**
     * Sum all expense line items for a vehicle (Engine, Denting/Painting,
     * Accessories, Tyre, Other) — feeds into Purchase Net Rate.
     */
    public function totalFor(Vehicle $vehicle): float
    {
        return (float) $vehicle->expenses()->sum('amount');
    }

    public function addLineItem(Vehicle $vehicle, array $data): VehicleExpense
    {
        $expense = $vehicle->expenses()->create($data);

        $this->syncPurchaseTotal($vehicle);

        return $expense;
    }

    public function removeLineItem(VehicleExpense $expense): void
    {
        $vehicle = $expense->vehicle;
        $expense->delete();

        $this->syncPurchaseTotal($vehicle);
    }

    /**
     * Re-sum expenses and push the new total into the purchase record's
     * net rate. Called any time an expense line item changes.
     */
    public function syncPurchaseTotal(Vehicle $vehicle): void
    {
        $purchase = $vehicle->purchase;

        if (! $purchase) {
            return;
        }

        $purchase->expenses_total = $this->totalFor($vehicle);
        $purchase->net_rate = $purchase->calculateNetRate();
        $purchase->save();

        if ($vehicle->isSold()) {
            app(SaleService::class)->recomputeProfitLoss($vehicle);
        }
    }
}
