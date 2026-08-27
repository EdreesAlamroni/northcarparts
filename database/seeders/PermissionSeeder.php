<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->seedDomain('user');
        $this->seedDomain('manufacturer');
        $this->seedDomain('category');
        $this->seedDomain('product');
        $this->seedDomain('specification');
        $this->seedDomain('news');
        $this->seedSettings();
    }

    private function seedDomain(string $domain): void
    {
        $viewAny = Permission::findOrCreate("{$domain}:view-any");
        $view = Permission::findOrCreate("{$domain}:view");
        $create = Permission::findOrCreate("{$domain}:create");
        $update = Permission::findOrCreate("{$domain}:update");
        $delete = Permission::findOrCreate("{$domain}:delete");

        $viewRole = Role::findOrCreate("{$domain}:role:view");
        $createRole = Role::findOrCreate("{$domain}:role:create");
        $updateRole = Role::findOrCreate("{$domain}:role:update");
        $deleteRole = Role::findOrCreate("{$domain}:role:delete");

        $viewRole->syncPermissions([$viewAny, $view]);
        $createRole->syncPermissions([$viewAny, $create]);
        $updateRole->syncPermissions([$viewAny, $view, $update]);
        $deleteRole->syncPermissions([$viewAny, $view, $delete]);
    }

    private function seedSettings(): void
    {
        $view = Permission::findOrCreate('settings:view');
        $update = Permission::findOrCreate('settings:update');

        $viewRole = Role::findOrCreate('settings:role:view');
        $updateRole = Role::findOrCreate('settings:role:update');

        $viewRole->syncPermissions([$view]);
        $updateRole->syncPermissions([$view, $update]);
    }
}
