<?php

namespace Database\Factories;

use App\Models\Specification;
use App\Models\SpecificationValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SpecificationValue>
 */
class SpecificationValueFactory extends Factory
{
    protected $model = SpecificationValue::class;

    public function definition(): array
    {
        return [
            'specification_id' => Specification::factory(),
            'value' => fake()->unique()->word(),
        ];
    }
}
