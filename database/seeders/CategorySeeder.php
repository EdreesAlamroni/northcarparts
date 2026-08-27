<?php

namespace Database\Seeders;

use App\Models\Category;
use App\ModelStates\Category\States\Visible;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'oil-filters', 'name' => 'فلاتر الزيت', 'sort_order' => 1],
            ['slug' => 'air-filters', 'name' => 'فلاتر الهواء', 'sort_order' => 2],
            ['slug' => 'cabin-filters', 'name' => 'فلاتر المقصورة', 'sort_order' => 3],
            ['slug' => 'fuel-filters', 'name' => 'فلاتر الوقود', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            Category::query()->firstOrCreate(
                ['slug' => $category['slug']],
                [
                    'uuid' => Str::uuid7()->toString(),
                    'name' => $category['name'],
                    'description' => null,
                    'technical_description' => null,
                    'sort_order' => $category['sort_order'],
                    'state' => Visible::name(),
                ],
            );
        }
    }
}
