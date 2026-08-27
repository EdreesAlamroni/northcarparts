<?php

namespace App\Queries\Dashboard;

use App\Models\Category;
use App\Models\Manufacturer;

class CatalogOverviewStats
{
    public function __invoke(): array
    {
        return [
            'categories' => Category::count(),
            'manufacturers' => Manufacturer::count(),
        ];
    }
}
