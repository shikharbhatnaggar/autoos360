<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // scoping to branch happens in the query (Vehicle::forUser)
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->isAdmin() || $vehicle->branch_id === $user->branch_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'branch_manager', 'staff');
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->isAdmin() || $vehicle->branch_id === $user->branch_id;
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        // Staff cannot delete records, only admins/managers of that branch
        return $user->isAdmin()
            || ($user->isBranchManager() && $vehicle->branch_id === $user->branch_id);
    }
}
