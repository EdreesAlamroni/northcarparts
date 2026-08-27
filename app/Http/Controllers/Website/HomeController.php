<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->visible()
            ->orderBy('sort_order')
            ->get();

        dd('HOME PAGE HERE');
    }
}
