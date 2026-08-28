<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Specification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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

    public function show(Specification $specification): View
    {
        Gate::authorize('view', $specification);

        $specification->loadCount(['products']);

        return view('pages.dashboard.specifications.show', [
            'specification' => $specification,
        ]);
    }

    public function destroy(Specification $specification): RedirectResponse
    {
        Gate::authorize('delete', $specification);

        $specification->delete();

        toast_success('delete');

        return to_route('dashboard.specifications.index');
    }
}
