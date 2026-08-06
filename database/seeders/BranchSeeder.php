<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['name' => 'Hyderabad - Ameerpet', 'code' => 'HYD-01', 'address' => 'Ameerpet, Hyderabad'],
            ['name' => 'Secunderabad', 'code' => 'SEC-01', 'address' => 'Secunderabad'],
            ['name' => 'Hyderabad - LB Nagar', 'code' => 'HYD-02', 'address' => 'LB Nagar, Hyderabad'],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate([ 'tenant_id' => tenant_id(), 'code' => $branch['code']], array_merge($branch, [
                'tenant_id' => tenant_id(),
            ]));
        }
    }
}
