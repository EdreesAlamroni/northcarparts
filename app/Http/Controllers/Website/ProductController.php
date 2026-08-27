<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function show(string $slug): View
    {
        $product = Product::query()
            ->visible()
            ->where('slug', '=', $slug)
            ->with(['category', 'manufacturer', 'specificationValues.specification:id,name'])
            ->first();

        abort_if($product === null, 404);

        dd('PRODUCT SHOW PAGE HERE');
    }
}
