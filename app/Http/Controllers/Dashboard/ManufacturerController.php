<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreManufacturerRequest;
use App\Http\Requests\Dashboard\UpdateManufacturerRequest;
use App\Models\Manufacturer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function create(): View
    {
        Gate::authorize('create', Manufacturer::class);

        return view('pages.dashboard.manufacturers.create');
    }

    public function store(StoreManufacturerRequest $request): RedirectResponse
    {
        Gate::authorize('create', Manufacturer::class);

        $manufacturer = DB::transaction(function () use ($request) {
            return Manufacturer::create($request->validated());
        });

        toast_success('create');

        return to_route('dashboard.manufacturers.show', $manufacturer);
    }

    public function show(Manufacturer $manufacturer): View
    {
        Gate::authorize('view', $manufacturer);

        $manufacturer->loadCount(['products']);

        return view('pages.dashboard.manufacturers.show', [
            'manufacturer' => $manufacturer,
        ]);
    }

    public function edit(Manufacturer $manufacturer): View
    {
        Gate::authorize('update', $manufacturer);

        return view('pages.dashboard.manufacturers.edit', [
            'manufacturer' => $manufacturer,
        ]);
    }

    public function update(UpdateManufacturerRequest $request, Manufacturer $manufacturer): RedirectResponse
    {
        Gate::authorize('update', $manufacturer);

        DB::transaction(function () use ($request, $manufacturer): void {
            $manufacturer->update($request->validated());
        });

        toast_success('update');

        return to_route('dashboard.manufacturers.show', $manufacturer);
    }

    public function destroy(Manufacturer $manufacturer): RedirectResponse
    {
        Gate::authorize('delete', $manufacturer);

        $manufacturer->delete();

        toast_success('delete');

        return to_route('dashboard.manufacturers.index');
    }
}
