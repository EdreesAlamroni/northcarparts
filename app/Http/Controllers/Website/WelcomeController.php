<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class WelcomeController extends Controller
{
    public function index(): View
    {
        return view('welcome');
    }
}
