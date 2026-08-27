<?php

namespace App\Queries\Dashboard;

use App\Models\Category;
use Illuminate\Support\Collection;

class ProductsByCategoryDistribution
{
    public function __invoke(): Collection
    {
        return Category::query()
            ->withCount('products')
            ->orderByDesc('products_count')
            ->get(['id', 'name'])
            ->map(fn (Category $category): array => [
                'id' => $category->id,
                'name' => $category->name,
                'count' => $category->products_count,
            ]);
    }
}
