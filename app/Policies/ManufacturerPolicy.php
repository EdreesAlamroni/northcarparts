<?php

namespace App\Policies;

use App\Models\Manufacturer;
use App\Models\User;

class ManufacturerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manufacturer:view-any');
    }

    public function view(User $user, Manufacturer $manufacturer): bool
    {
        return $user->can('manufacturer:view');
    }

    public function create(User $user): bool
    {
        return $user->can('manufacturer:create');
    }

    public function update(User $user, Manufacturer $manufacturer): bool
    {
        return $user->can('manufacturer:update');
    }

    public function delete(User $user, Manufacturer $manufacturer): bool
    {
        return $user->can('manufacturer:delete');
    }
}
