<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;

class BrandPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('brand:view-any');
    }

    public function view(User $user, Brand $brand): bool
    {
        return $user->can('brand:view');
    }

    public function create(User $user): bool
    {
        return $user->can('brand:create');
    }

    public function update(User $user, Brand $brand): bool
    {
        return $user->can('brand:update');
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $user->can('brand:delete');
    }
}
