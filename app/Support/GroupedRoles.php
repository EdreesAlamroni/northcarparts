<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class GroupedRoles
{
    public function all(?array $ids = null): Collection
    {
        return Role::query()
            ->oldest()
            ->when(filled($ids), function (Builder $query) use ($ids): Builder {
                return $query->whereIn('id', $ids);
            })
            ->pluck('name', 'id')
            ->map(function (string $name, int $id): object {
                return (object) [
                    'id' => $id,
                    'name' => $name,
                ];
            })
            ->groupBy(function (object $role): string {
                return Str::before($role->name, ':');
            })
            ->map(function (Collection $roles, string $group): object {
                return (object) [
                    'key' => $group,
                    'label' => __("roles.{$group}.label"),
                    'roles' => $roles->map(function (object $role) use ($group): object {
                        return (object) [
                            'id' => $role->id,
                            'name' => $role->name,
                            'label' => __("roles.{$group}.values.{$role->name}"),
                        ];
                    })->values(),
                ];
            })
            ->values();
    }

    public function forUser(User $user): Collection
    {
        $roleIds = $user->roles()->pluck('roles.id')->all();

        if ($roleIds === []) {
            return collect([]);
        }

        return $this->all($roleIds);
    }
}
