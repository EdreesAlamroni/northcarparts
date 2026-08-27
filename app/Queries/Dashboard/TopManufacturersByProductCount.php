<?php

namespace App\Queries\Dashboard;

use App\Models\Manufacturer;
use Illuminate\Support\Collection;

class TopManufacturersByProductCount
{
    public function __invoke(int $limit = 5): Collection
    {
        return Manufacturer::query()
            ->withCount('products')
            ->whereHas('products')
            ->orderByDesc('products_count')
            ->limit($limit)
            ->get(['id', 'name'])
            ->map(fn (Manufacturer $manufacturer): array => [
                'id' => $manufacturer->id,
                'name' => $manufacturer->name,
                'count' => $manufacturer->products_count,
            ]);
    }
}
