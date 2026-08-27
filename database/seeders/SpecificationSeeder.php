<?php

namespace Database\Seeders;

use App\Models\Specification;
use App\Models\SpecificationValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpecificationSeeder extends Seeder
{
    public function run(): void
    {
        $specifications = [
            'Height' => ['86 mm', '72 mm', '58 mm', '56 mm', '95 mm', '144 mm'],
            'Diameter' => ['68 mm', '82 mm', '65 mm', '80 mm'],
            'Length' => ['247 mm', '225 mm'],
            'Width' => ['193 mm', '235 mm'],
            'Thickness' => ['1.5 mm', '2 mm', '3 mm'],
        ];

        foreach ($specifications as $name => $values) {
            $specification = Specification::query()->firstOrCreate([
                'name' => $name,
            ], [
                'uuid' => Str::uuid7()->toString(),
            ]);

            foreach ($values as $value) {
                SpecificationValue::query()->firstOrCreate([
                    'specification_id' => $specification->id,
                    'value' => $value,
                ]);
            }
        }
    }
}
