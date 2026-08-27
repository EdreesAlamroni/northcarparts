<?php

namespace Database\Seeders;

use App\Enums\FilterType;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Specification;
use App\ModelStates\Product\States\Visible;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'code' => 'NCP-101',
                'category_slug' => 'oil-filters',
                'manufacturer_name' => 'Toyota',
                'name' => 'فلتر زيت Toyota Spin-On',
                'filter_type' => FilterType::SpinOnOilFilter,
                'oem_number' => '90915-54940',
                'sort_order' => 1,
            ],
            [
                'code' => 'NCP-102',
                'category_slug' => 'oil-filters',
                'manufacturer_name' => 'Bosch',
                'name' => 'فلتر زيت Bosch Eco',
                'filter_type' => FilterType::EcoOilFilter,
                'oem_number' => '0451103316',
                'sort_order' => 2,
            ],
            [
                'code' => 'NCP-201',
                'category_slug' => 'air-filters',
                'manufacturer_name' => 'Mann-Filter',
                'name' => 'فلتر هواء Mann-Filter',
                'filter_type' => FilterType::AirFilter,
                'oem_number' => 'C 25 115',
                'sort_order' => 1,
            ],
            [
                'code' => 'NCP-301',
                'category_slug' => 'cabin-filters',
                'manufacturer_name' => 'Mahle',
                'name' => 'فلتر مقصورة Mahle',
                'filter_type' => FilterType::AirFilter,
                'oem_number' => 'LA 461',
                'sort_order' => 1,
            ],
            [
                'code' => 'NCP-401',
                'category_slug' => 'fuel-filters',
                'manufacturer_name' => 'Denso',
                'name' => 'فلتر وقود Denso',
                'filter_type' => FilterType::SpinOnOilFilter,
                'oem_number' => '23300-78090',
                'sort_order' => 1,
            ],
            [
                'code' => 'NCP-402',
                'category_slug' => 'fuel-filters',
                'manufacturer_name' => 'Hyundai',
                'name' => 'فلتر وقود Hyundai',
                'filter_type' => FilterType::SpinOnOilFilter,
                'oem_number' => '31973-2E900',
                'sort_order' => 2,
            ],
        ];

        $categories = Category::query()->pluck('id', 'slug');
        $manufacturers = Manufacturer::query()->pluck('id', 'name');

        $height = Specification::query()->where('name', '=', 'Height')->firstOrFail();
        $diameter = Specification::query()->where('name', '=', 'Diameter')->firstOrFail();
        $length = Specification::query()->where('name', '=', 'Length')->firstOrFail();
        $width = Specification::query()->where('name', '=', 'Width')->firstOrFail();

        foreach ($products as $product) {
            $createdProduct = Product::query()->firstOrCreate(
                ['code' => $product['code']],
                [
                    'uuid' => Str::uuid7()->toString(),
                    'category_id' => $categories[$product['category_slug']],
                    'oem_manufacturer_id' => $manufacturers[$product['manufacturer_name']],
                    'slug' => Str::slug($product['code']),
                    'name' => $product['name'],
                    'filter_type' => $product['filter_type'],
                    'oem_number' => $product['oem_number'],
                    'qr_code_redirect_url' => 'https://example.com?product='.$product['code'],
                    'sort_order' => $product['sort_order'],
                    'state' => Visible::name(),
                ],
            );

            $specificationValueIds = match ($product['code']) {
                'NCP-101' => [
                    $height->values()->where('value', '=', '86 mm')->firstOrFail()->id,
                    $diameter->values()->where('value', '=', '68 mm')->firstOrFail()->id,
                ],
                'NCP-102' => [
                    $height->values()->where('value', '=', '72 mm')->firstOrFail()->id,
                    $diameter->values()->where('value', '=', '82 mm')->firstOrFail()->id,
                ],
                'NCP-201' => [
                    $length->values()->where('value', '=', '247 mm')->firstOrFail()->id,
                    $width->values()->where('value', '=', '193 mm')->firstOrFail()->id,
                    $height->values()->where('value', '=', '58 mm')->firstOrFail()->id,
                ],
                'NCP-301' => [
                    $length->values()->where('value', '=', '225 mm')->firstOrFail()->id,
                    $width->values()->where('value', '=', '235 mm')->firstOrFail()->id,
                    $height->values()->where('value', '=', '56 mm')->firstOrFail()->id,
                ],
                'NCP-401' => [
                    $height->values()->where('value', '=', '95 mm')->firstOrFail()->id,
                    $diameter->values()->where('value', '=', '65 mm')->firstOrFail()->id,
                ],
                'NCP-402' => [
                    $height->values()->where('value', '=', '144 mm')->firstOrFail()->id,
                    $diameter->values()->where('value', '=', '80 mm')->firstOrFail()->id,
                ],
                default => [],
            };

            $createdProduct->specificationValues()->sync($specificationValueIds);
        }
    }
}
