<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\SpecificationValue;
use App\ModelStates\Product\States\Visible;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $code = sprintf(
            'NCP-%03d',
            fake()->unique()->numberBetween(10, 999),
        );

        return [
            'uuid' => Str::uuid7()->toString(),
            'category_id' => Category::factory(),
            'oem_manufacturer_id' => Manufacturer::factory(),
            'slug' => Str::slug($code),
            'code' => $code,
            'oem_number' => fake()->bothify('??###-#####'),
            'sort_order' => fake()->numberBetween(1, 100),
            'state' => Visible::name(),
        ];
    }

    public function withSpecificationValues(array|int $specificationValueIds = 2): static
    {
        return $this->afterCreating(function (Product $product) use ($specificationValueIds): void {
            $ids = is_int($specificationValueIds)
                ? SpecificationValue::factory()->count($specificationValueIds)->create()->pluck('id')->all()
                : $specificationValueIds;

            $product->specificationValues()->sync($ids);
        });
    }
}
