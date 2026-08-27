<?php

namespace App\Policies;

use App\Models\Specification;
use App\Models\User;

class SpecificationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('specification:view-any');
    }

    public function view(User $user, Specification $specification): bool
    {
        return $user->can('specification:view');
    }

    public function create(User $user): bool
    {
        return $user->can('specification:create');
    }

    public function update(User $user, Specification $specification): bool
    {
        return $user->can('specification:update');
    }

    public function delete(User $user, Specification $specification): bool
    {
        return $user->can('specification:delete');
    }
}
