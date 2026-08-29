<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Specification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class SpecificationController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Specification::class);

        $specifications = QueryBuilder::for(Specification::class)
            ->select([
                'id',
                'uuid',
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

        return view('pages.dashboard.specifications.index', [
            'specifications' => $specifications,
        ]);
    }
}
