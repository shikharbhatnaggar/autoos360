<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Broker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Base Vehicle Query
        |--------------------------------------------------------------------------
        */

        $vehicles = Vehicle::forUser($user)
            ->with([
                'branch',
                'purchase.broker',
                'sale',
                'expenses',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Available Vehicles
        |--------------------------------------------------------------------------
        */

        $inStockCount = (clone $vehicles)
            ->inStock()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Sold Vehicles
        |--------------------------------------------------------------------------
        */

        $soldVehicles = (clone $vehicles)
            ->sold()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Total Sale
        |--------------------------------------------------------------------------
        */

        $totalSale = $soldVehicles->sum(function ($vehicle) {
            return (float) ($vehicle->sale->sale_rate ?? 0);
        });

        /*
        |--------------------------------------------------------------------------
        | Total Purchase
        |--------------------------------------------------------------------------
        */

        $allVehicles = (clone $vehicles)->get();

        $totalPurchase = $allVehicles->sum(function ($vehicle) {
            return (float) ($vehicle->purchase->purchase_rate ?? 0);
        });

        /*
        |--------------------------------------------------------------------------
        | Total Expenses
        |--------------------------------------------------------------------------
        */

        $totalExpense = $allVehicles->sum(function ($vehicle) {
            return $vehicle->expenses->sum(function ($expense) {
                return (float) $expense->amount;
            });
        });

        /*
        |--------------------------------------------------------------------------
        | Current Month Sales
        |--------------------------------------------------------------------------
        */

        $soldThisMonth = (clone $vehicles)
            ->sold()
            ->whereHas('sale', function ($query) {
                $query
                    ->whereMonth('sale_date', now()->month)
                    ->whereYear('sale_date', now()->year);
            })
            ->get();

        $monthProfit = $soldThisMonth->sum(function ($vehicle) {
            return (float) ($vehicle->sale->profit_loss ?? 0);
        });

        /*
        |--------------------------------------------------------------------------
        | Brokers
        |--------------------------------------------------------------------------
        */

        $totalBrokers = Broker::count();

        /*
        |--------------------------------------------------------------------------
        | Broker Commission
        |--------------------------------------------------------------------------
        |
        | Broker commission is stored in VehiclePurchase.commission.
        |
        */

        $totalCommission = $allVehicles->sum(function ($vehicle) {
            return (float) ($vehicle->purchase->commission ?? 0);
        });

        /*
        |--------------------------------------------------------------------------
        | Branch Statistics
        |--------------------------------------------------------------------------
        */

        $branchStats = $allVehicles
            ->groupBy('branch_id')
            ->map(function ($branchVehicles) {

                $branch = $branchVehicles->first()->branch;

                return [
                    'name' => $branch?->name ?? 'Unknown',

                    'stock' => $branchVehicles
                        ->where('status', 'in_stock')
                        ->count(),

                    'purchase' => $branchVehicles->sum(function ($vehicle) {
                        return (float) ($vehicle->purchase->purchase_rate ?? 0);
                    }),

                    'sale' => $branchVehicles->sum(function ($vehicle) {
                        return (float) ($vehicle->sale->sale_rate ?? 0);
                    }),

                    'expense' => $branchVehicles->sum(function ($vehicle) {
                        return $vehicle->expenses->sum(function ($expense) {
                            return (float) $expense->amount;
                        });
                    }),
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Recent Vehicles
        |--------------------------------------------------------------------------
        */

        $recent = Vehicle::forUser($user)
            ->with([
                'branch',
                'purchase',
                'sale',
            ])
            ->latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard.index', [
            'inStockCount'       => $inStockCount,
            'soldThisMonthCount' => $soldThisMonth->count(),
            'monthProfit'        => $monthProfit,

            'totalSale'          => $totalSale,
            'totalPurchase'      => $totalPurchase,
            'totalExpense'       => $totalExpense,

            'totalBrokers'       => $totalBrokers,
            'totalCommission'    => $totalCommission,

            'branchStats'        => $branchStats,

            'recent'             => $recent,
        ]);
    }
}