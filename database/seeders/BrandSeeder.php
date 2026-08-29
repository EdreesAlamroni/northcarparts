<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'MULLER',
            'MANN',
            'MAHLE',
            'BOSCH',
            'PURFLUX',
            'WIX',
            'FRAM',
            'UFI',
            'TECNECO',
        ];

        foreach ($brands as $name) {
            Brand::query()->firstOrCreate(['name' => $name]);
        }
    }
}
