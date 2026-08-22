<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Broker;

class BrokerSeeder extends Seeder
{
    public function run(): void
    {
        $brokers = [
            ['name' => 'Ravi Kumar', 'mobile' => '9876543210'],
            ['name' => 'Suresh Auto Deals', 'mobile' => '9123456780'],
        ];

        foreach ($brokers as $broker) {
            Broker::updateOrCreate(['name' => $broker['name']], $broker);
        }
    }
}
