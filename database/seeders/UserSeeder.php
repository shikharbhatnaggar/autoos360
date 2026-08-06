<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Branch;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'branch_manager')->first();
        $staff = Role::where('name', 'staff')->first();
        $branch1 = Branch::first();

        User::updateOrCreate(
            ['email' => 'admin@maharajah.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role_id' => $admin->id,
                'branch_id' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@maharajah.local'],
            [
                'name' => 'Branch Manager',
                'password' => Hash::make('password'),
                'role_id' => $manager->id,
                'branch_id' => $branch1->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@maharajah.local'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role_id' => $staff->id,
                'branch_id' => $branch1->id,
            ]
        );
    }
}
