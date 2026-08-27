<?php

namespace Database\Factories;

use App\Models\Category;
use App\ModelStates\Category\States\Visible;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'uuid' => Str::uuid7()->toString(),
            'slug' => Str::slug($name),
            'name' => $name,
            'description' => fake()->paragraph(),
            'technical_description' => fake()->paragraph(),
            'sort_order' => fake()->numberBetween(0, 100),
            'state' => Visible::name(),
        ];
    }
}
