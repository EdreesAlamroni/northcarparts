<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Contracts\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $newsItems = News::query()
            ->visible()
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(12);

        dd('NEWS PAGE HERE');
    }

    public function show(string $slug): View
    {
        $news = News::query()
            ->visible()
            ->where('slug', $slug)
            ->first();

        abort_if($news === null, 404);

        dd('NEWS SHOW PAGE HERE');
    }
}
