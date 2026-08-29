<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreBrandRequest;
use App\Http\Requests\Dashboard\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class BrandController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Brand::class);

        $brands = QueryBuilder::for(Brand::class)
            ->select([
                'id',
                'name',
                'created_at',
            ])
            ->withCount(['products'])
            ->allowedFilters('name')
            ->paginate()
            ->withQueryString()
            ->appends($request->query());

        return view('pages.dashboard.brands.index', [
            'brands' => $brands,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Brand::class);

        return view('pages.dashboard.brands.create');
    }

    public function store(StoreBrandRequest $request): RedirectResponse
    {
        Gate::authorize('create', Brand::class);

        $brand = DB::transaction(function () use ($request): Brand {
            return Brand::create($request->validated());
        });

        toast_success('create');

        return to_route('dashboard.brands.show', $brand);
    }

    public function show(Brand $brand): View
    {
        Gate::authorize('view', $brand);

        $brand->loadCount(['products']);

        return view('pages.dashboard.brands.show', [
            'brand' => $brand,
        ]);
    }

    public function edit(Brand $brand): View
    {
        Gate::authorize('update', $brand);

        return view('pages.dashboard.brands.edit', [
            'brand' => $brand,
        ]);
    }

    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        Gate::authorize('update', $brand);

        DB::transaction(function () use ($request, $brand): void {
            $brand->update($request->validated());
        });

        toast_success('update');

        return to_route('dashboard.brands.show', $brand);
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        Gate::authorize('delete', $brand);

        $brand->delete();

        toast_success('delete');

        return to_route('dashboard.brands.index');
    }
}
