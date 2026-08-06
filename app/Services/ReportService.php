<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Carbon;

class ReportService
{
    /** Profit & Loss report across sold vehicles, scoped to the user's branch access. */
    public function profitLoss(User $user, ?string $branchId = null, ?string $from = null, ?string $to = null)
    {
        $query = Vehicle::query()
            ->forUser($user)
            ->sold()
            ->with(['purchase', 'sale', 'branch']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($from) {
            $query->whereHas('sale', fn ($q) => $q->whereDate('sale_date', '>=', Carbon::parse($from)));
        }

        if ($to) {
            $query->whereHas('sale', fn ($q) => $q->whereDate('sale_date', '<=', Carbon::parse($to)));
        }

        $vehicles = $query->get();

        return [
            'vehicles' => $vehicles,
            'total_profit' => $vehicles->sum(fn ($v) => (float) $v->sale->profit_loss),
            'total_sales_value' => $vehicles->sum(fn ($v) => (float) $v->sale->sale_rate),
            'count' => $vehicles->count(),
        ];
    }

    /** Current stock-in-hand report, scoped to branch access. */
    public function stockInHand(User $user, ?string $branchId = null)
    {
        $query = Vehicle::query()
            ->forUser($user)
            ->inStock()
            ->with(['purchase', 'branch']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $vehicles = $query->get();

        return [
            'vehicles' => $vehicles,
            'capital_locked' => $vehicles->sum(fn ($v) => (float) ($v->purchase->net_rate ?? 0)),
            'count' => $vehicles->count(),
        ];
    }

    /** Broker-wise commission paid on the purchase side. */
    public function brokerCommissions(User $user, ?string $from = null, ?string $to = null)
    {
        $query = Vehicle::query()
            ->forUser($user)
            ->whereHas('purchase', fn ($q) => $q->where('reference_type', 'broker'))
            ->with(['purchase.broker']);

        if ($from) {
            $query->whereHas('purchase', fn ($q) => $q->whereDate('purchase_date', '>=', Carbon::parse($from)));
        }
        if ($to) {
            $query->whereHas('purchase', fn ($q) => $q->whereDate('purchase_date', '<=', Carbon::parse($to)));
        }

        return $query->get()
            ->groupBy(fn ($v) => $v->purchase->broker?->name ?? 'Unknown')
            ->map(fn ($group) => [
                'count' => $group->count(),
                'total_commission' => $group->sum(fn ($v) => (float) $v->purchase->commission),
            ]);
    }
}
