<?php

namespace Database\Factories;

use App\Models\News;
use App\ModelStates\News\States\Visible;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'uuid' => Str::uuid7()->toString(),
            'slug' => Str::slug($title),
            'title' => $title,
            'content' => fake()->paragraphs(3, true),
            'published_at' => today(),
            'state' => Visible::name(),
        ];
    }
}
