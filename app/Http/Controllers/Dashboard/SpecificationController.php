<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreSpecificationRequest;
use App\Models\Specification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function create(): View
    {
        Gate::authorize('create', Specification::class);

        return view('pages.dashboard.specifications.create');
    }

    public function store(StoreSpecificationRequest $request): RedirectResponse
    {
        Gate::authorize('create', Specification::class);

        $specification = DB::transaction(function () use ($request) {
            /** @var Specification $specification */
            $specification = Specification::create([
                'name' => $request->validated('name'),
            ]);

            if ($values = $this->normalizeValues($request->input('values'))) {
                $values = collect($values)->map(fn (string $value): array => ['value' => $value])->all();

                $specification->values()->createMany($values);
            }

            return $specification;
        });

        toast_success('create');

        return to_route('dashboard.specifications.show', $specification);
    }

    public function show(Specification $specification): View
    {
        Gate::authorize('view', $specification);

        $specification->load([
            'values' => fn ($query) => $query->orderBy('value'),
        ])->loadCount('products');

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

    private function normalizeValues(?array $values): array
    {
        if ($values === null) {
            return [];
        }

        return collect($values)
            ->filter(fn (?string $value): bool => filled($value))
            ->map(fn (?string $value): string => (string) $value)
            ->values()
            ->all();
    }
}
