<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\VehicleSale;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SaleService
{
    /**
     * Record the sale (purchaser) leg of a vehicle — mirrors the bottom
     * half of the memo. Flips the vehicle to "sold" and computes P&L.
     */
    public function recordSale(Vehicle $vehicle, array $saleData): VehicleSale
    {
        if ($vehicle->isSold()) {
            throw new RuntimeException('This vehicle is already marked as sold.');
        }

        if (! $vehicle->purchase) {
            throw new RuntimeException('Cannot sell a vehicle with no purchase record on file.');
        }

        return DB::transaction(function () use ($vehicle, $saleData) {
            $sale = $vehicle->sale()->create($saleData);

            $sale->net_rate = $sale->calculateNetRate();
            $sale->profit_loss = $this->calculateProfitLoss($sale, $vehicle);
            $sale->save();

            $vehicle->update(['status' => 'sold']);

            $this->log('sold', $vehicle);

            return $sale;
        });
    }

    public function updateSale(Vehicle $vehicle, array $saleData): VehicleSale
    {
        return DB::transaction(function () use ($vehicle, $saleData) {
            $sale = $vehicle->sale;
            $sale->update($saleData);

            $sale->net_rate = $sale->calculateNetRate();
            $sale->profit_loss = $this->calculateProfitLoss($sale, $vehicle);
            $sale->save();

            $this->log('updated_sale', $vehicle);

            return $sale;
        });
    }

    /**
     * Recompute Profit/Loss without touching sale inputs — used when the
     * purchase side (rate/commission/expenses) changes after the sale.
     */
    public function recomputeProfitLoss(Vehicle $vehicle): void
    {
        $sale = $vehicle->sale;

        if (! $sale) {
            return;
        }

        $sale->profit_loss = $this->calculateProfitLoss($sale, $vehicle);
        $sale->save();
    }

    /**
     * Profit/Loss = Sale Net Rate - Purchase Net Rate.
     */
    protected function calculateProfitLoss(VehicleSale $sale, Vehicle $vehicle): float
    {
        $purchaseNetRate = (float) $vehicle->purchase->net_rate;

        return round((float) $sale->net_rate - $purchaseNetRate, 2);
    }

    /** Undo a sale, e.g. if it was entered by mistake — puts vehicle back in stock. */
    public function cancelSale(Vehicle $vehicle): void
    {
        DB::transaction(function () use ($vehicle) {
            $vehicle->sale?->delete();
            $vehicle->update(['status' => 'in_stock']);
            $this->log('sale_cancelled', $vehicle);
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
}
