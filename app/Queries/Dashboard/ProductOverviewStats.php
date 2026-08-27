<?php

namespace App\Queries\Dashboard;

use App\Models\Product;
use App\ModelStates\Product\States\Visible;
use Illuminate\Support\Carbon;

class ProductOverviewStats
{
    public function __invoke(): array
    {
        $stats = Product::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN state = ? THEN 1 ELSE 0 END) as visible', [Visible::name()])
            ->selectRaw('SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as added_this_month', [Carbon::now()->startOfMonth()])
            ->first();

        return [
            'total' => (int) $stats->total,
            'visible' => (int) $stats->visible,
            'added_this_month' => (int) $stats->added_this_month,
        ];
    }
}
