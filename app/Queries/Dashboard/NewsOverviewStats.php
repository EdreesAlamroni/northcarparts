<?php

namespace App\Queries\Dashboard;

use App\Models\News;

class NewsOverviewStats
{
    public function __invoke(): array
    {
        return [
            'total' => News::count(),
            'visible' => News::visible()->count(),
        ];
    }
}
