<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Specification;
use App\ModelStates\Product\States\Visible;
use App\Support\PartNumberNormalizer;
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
                'oem_number' => '90915-54940',
                'sort_order' => 1,
            ],
            [
                'code' => 'NCP-102',
                'category_slug' => 'oil-filters',
                'manufacturer_name' => 'Bosch',
                'oem_number' => '0451103316',
                'sort_order' => 2,
            ],
            [
                'code' => 'NCP-201',
                'category_slug' => 'air-filters',
                'manufacturer_name' => 'Mann-Filter',
                'oem_number' => 'C 25 115',
                'sort_order' => 1,
            ],
            [
                'code' => 'NCP-301',
                'category_slug' => 'cabin-filters',
                'manufacturer_name' => 'Mahle',
                'oem_number' => 'LA 461',
                'sort_order' => 1,
            ],
            [
                'code' => 'NCP-401',
                'category_slug' => 'fuel-filters',
                'manufacturer_name' => 'Denso',
                'oem_number' => '23300-78090',
                'sort_order' => 1,
            ],
            [
                'code' => 'NCP-402',
                'category_slug' => 'fuel-filters',
                'manufacturer_name' => 'Hyundai',
                'oem_number' => '31973-2E900',
                'sort_order' => 2,
            ],
        ];

        $categories = Category::query()->pluck('id', 'slug');
        $manufacturers = Manufacturer::query()->pluck('id', 'name');
        $brands = Brand::query()->pluck('id', 'name');

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
                    'oem_number' => $product['oem_number'],
                    'sort_order' => $product['sort_order'],
                    'state' => Visible::name(),
                ],
            );

            $specifications = match ($product['code']) {
                'NCP-101' => [
                    $height->id => ['value' => '86 mm'],
                    $diameter->id => ['value' => '68 mm'],
                ],
                'NCP-102' => [
                    $height->id => ['value' => '72 mm'],
                    $diameter->id => ['value' => '82 mm'],
                ],
                'NCP-201' => [
                    $length->id => ['value' => '247 mm'],
                    $width->id => ['value' => '193 mm'],
                    $height->id => ['value' => '58 mm'],
                ],
                'NCP-301' => [
                    $length->id => ['value' => '225 mm'],
                    $width->id => ['value' => '235 mm'],
                    $height->id => ['value' => '56 mm'],
                ],
                'NCP-401' => [
                    $height->id => ['value' => '95 mm'],
                    $diameter->id => ['value' => '65 mm'],
                ],
                'NCP-402' => [
                    $height->id => ['value' => '144 mm'],
                    $diameter->id => ['value' => '80 mm'],
                ],
                default => [],
            };

            $createdProduct->specifications()->sync($specifications);

            $crossReferences = match ($product['code']) {
                'NCP-101' => [
                    'MANN' => 'W 920/21',
                    'MULLER' => 'FO3142',
                    'BOSCH' => '0451103316',
                ],
                'NCP-102' => [
                    'WIX' => 'WL7067',
                    'FRAM' => 'PH4967',
                    'UFI' => '23.120.00',
                ],
                'NCP-201' => [
                    'MANN' => 'C 25 115',
                    'MAHLE' => 'LX 3483',
                    'PURFLUX' => 'A1318',
                ],
                'NCP-301' => [
                    'MULLER' => 'FC3142',
                    'TECNECO' => 'CK3142',
                    'UFI' => '53.235.00',
                ],
                'NCP-401' => [
                    'BOSCH' => 'N2033',
                    'WIX' => 'WF8048',
                ],
                'NCP-402' => [
                    'MANN' => 'WK 8019',
                    'FRAM' => 'PS10409',
                ],
                default => [],
            };

            $syncCrossReferences = collect($crossReferences)
                ->filter(function (string $referenceCode, string $brandName) use ($brands): bool {
                    return isset($brands[$brandName]);
                })
                ->mapWithKeys(function (string $referenceCode, string $brandName) use ($brands): array {
                    return [
                        $brands[$brandName] => [
                            'reference_code' => $referenceCode,
                            'reference_code_normalized' => PartNumberNormalizer::normalize($referenceCode),
                        ],
                    ];
                })
                ->all();

            $createdProduct->brands()->sync($syncCrossReferences);
        }
    }
}
