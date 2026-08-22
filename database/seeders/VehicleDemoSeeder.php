<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Branch;
use App\Models\Broker;
use App\Services\VehicleService;
use App\Services\SaleService;

class VehicleDemoSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();
        $broker = Broker::first();

        $vehicleService = app(VehicleService::class);
        $saleService = app(SaleService::class);

        // Vehicle 1: in stock, purchased with expenses, not yet sold
        $v1 = $vehicleService->createWithPurchase([
            'branch_id' => $branch->id,
            'sr_no' => 'SR-001',
            'memo_no' => 'M-001',
            'vehicle_no' => 'TS09AB1234',
            'model' => 'Maruti Swift VDi 2018',
        ], [
            'seller_name' => 'Mohammed Irfan',
            'seller_address' => 'Malakpet, Hyderabad',
            'seller_mobile' => '9000011111',
            'reference_type' => 'broker',
            'broker_id' => $broker->id,
            'purchase_date' => now()->subDays(20),
            'purchase_rate' => 280000,
            'commission' => 5000,
        ], [
            ['category' => 'engine', 'amount' => 3000],
            ['category' => 'denting_painting', 'amount' => 4000],
            ['category' => 'tyre', 'amount' => 6000, 'percentage' => 40],
        ]);

        // Vehicle 2: purchased and sold (matches the memo example: sale 300000, comm 10000)
        $v2 = $vehicleService->createWithPurchase([
            'branch_id' => $branch->id,
            'sr_no' => 'SR-002',
            'memo_no' => 'M-002',
            'vehicle_no' => 'TS10CD5678',
            'model' => 'Hyundai i20 Sportz 2019',
        ], [
            'seller_name' => 'Lakshmi Narayana',
            'seller_address' => 'Kukatpally, Hyderabad',
            'seller_mobile' => '9000022222',
            'reference_type' => 'direct',
            'purchase_date' => now()->subDays(40),
            'purchase_rate' => 260000,
            'commission' => 4000,
        ], [
            ['category' => 'accessories', 'amount' => 2000],
        ]);

        $saleService->recordSale($v2, [
            'purchaser_name' => 'Kiran Reddy',
            'purchaser_address' => 'Secunderabad',
            'purchaser_mobile' => '9000033333',
            'reference_medium' => 'Branch Walk-in',
            'sale_date' => now()->subDays(5),
            'sale_rate' => 300000,
            'commission' => 10000,
        ]);
    }
}
