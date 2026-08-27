<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    public function run(): void
    {
        $manufacturers = [
            'Toyota',
            'Bosch',
            'Mann-Filter',
            'Mahle',
            'Denso',
            'Hyundai',
        ];

        foreach ($manufacturers as $name) {
            Manufacturer::query()->firstOrCreate(
                ['name' => $name],
            );
        }
    }
}
