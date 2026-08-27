<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class QrRedirectController extends Controller
{
    public function __invoke(string $qrCodeRedirectUrl): RedirectResponse
    {
        $product = Product::query()
            ->visible()
            ->where('qr_code_redirect_url', '=', $qrCodeRedirectUrl)
            ->first();

        abort_if($product === null, 404);

        dd('QR REDIRECT PAGE HERE');
    }
}
