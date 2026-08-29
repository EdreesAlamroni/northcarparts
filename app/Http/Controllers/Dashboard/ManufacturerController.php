<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class ManufacturerController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Manufacturer::class);

        $manufacturers = QueryBuilder::for(Manufacturer::class)
            ->select([
                'id',
                'name',
                'created_at',
            ])
            ->withCount(['products'])
            ->allowedFilters(
                'name',
            )
            ->paginate()
            ->withQueryString()
            ->appends($request->query());

        return view('pages.dashboard.manufacturers.index', [
            'manufacturers' => $manufacturers,
        ]);
    }
}
