<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    public function show(string $slug): View
    {
        $category = Category::query()
            ->visible()
            ->where('slug', '=', $slug)
            ->first();

        abort_if($category === null, 404);

        $products = $category->products()
            ->visible()
            ->with('manufacturer')
            ->orderBy('sort_order')
            ->get();

        dd('CATEGORY PAGE HERE');
    }
}
