<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->administrator()->create([
            'name' => 'مدير النظام',
            'email' => 'info@example.com',
        ]);

        Artisan::call('seed:permissions');

        $this->call([
            CategorySeeder::class,
            ManufacturerSeeder::class,
            SpecificationSeeder::class,
            ProductSeeder::class,
            NewsSeeder::class,
        ]);
    }
}
