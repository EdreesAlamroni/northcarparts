<?php

namespace Database\Seeders;

use App\Models\Specification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpecificationSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Height',
            'Diameter',
            'Length',
            'Width',
            'Thickness',
        ];

        foreach ($names as $name) {
            Specification::query()->firstOrCreate([
                'name' => $name,
            ], [
                'uuid' => Str::uuid7()->toString(),
            ]);
        }
    }
}
