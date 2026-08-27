<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class QrRedirectController extends Controller
{
    public function __invoke(string $slug): RedirectResponse
    {
        $product = Product::query()
            ->visible()
            ->where('slug', '=', $slug)
            ->first();

        abort_if($product === null, 404);

        return redirect()->route('website.products.show', $product->slug);
    }
}
