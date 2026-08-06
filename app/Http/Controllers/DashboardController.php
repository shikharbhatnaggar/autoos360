<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // dd(app(\App\Services\TenantManager::class)->get());
        // dd(tenant()->name);
        
        $inStockCount = Vehicle::forUser($user)->inStock()->count();
        $soldThisMonth = Vehicle::forUser($user)->sold()
            ->whereHas('sale', fn ($q) => $q->whereMonth('sale_date', now()->month))
            ->with('sale')
            ->get();

        $monthProfit = $soldThisMonth->sum(fn ($v) => (float) $v->sale->profit_loss);

        $recent = Vehicle::forUser($user)
            ->with(['branch', 'purchase', 'sale'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', [
            'inStockCount' => $inStockCount,
            'soldThisMonthCount' => $soldThisMonth->count(),
            'monthProfit' => $monthProfit,
            'recent' => $recent,
        ]);
    }
}
