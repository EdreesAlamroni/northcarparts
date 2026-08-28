<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreProductRequest;
use App\Http\Requests\Dashboard\UpdateProductRequest;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Specification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Product::class);

        $products = QueryBuilder::for(Product::class)
            ->select([
                'id',
                'uuid',
                'category_id',
                'code',
                'state',
                'created_at',
            ])
            ->with(['category:id,name'])
            ->allowedFilters(
                'code',
                'slug',
            )
            ->latest()
            ->paginate()
            ->withQueryString()
            ->appends($request->query());

        return view('pages.dashboard.products.index', [
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Product::class);

        return view('pages.dashboard.products.create', [
            'categories' => Category::list(),
            'manufacturers' => Manufacturer::list(),
            'states' => Product::getStateOptions(),
            'specifications' => Specification::list(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Gate::authorize('create', Product::class);

        $product = DB::transaction(function () use ($request) {
            $validatedData = $request->safe()->except([
                'image',
                'specifications',
            ]);

            /** @var Product $product */
            $product = Product::create($validatedData);

            if ($request->hasFile('image')) {
                $product->addMediaFromRequest('image')->toMediaCollection('image');
            }

            $this->syncSpecifications($product, $request->validated('specifications') ?? []);

            return $product;
        });

        toast_success('create');

        return to_route('dashboard.products.show', $product);
    }

    public function show(Product $product): View
    {
        Gate::authorize('view', $product);

        $product->load([
            'category:id,name',
            'manufacturer:id,name',
            'specifications:id,name',
        ]);

        return view('pages.dashboard.products.show', [
            'product' => $product,
            'specifications' => $product->specifications->sortBy('name')->values(),
        ]);
    }

    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        $product->load(['specifications:id,name']);

        return view('pages.dashboard.products.edit', [
            'product' => $product,
            'categories' => Category::list(),
            'manufacturers' => Manufacturer::list(),
            'specifications' => Specification::list(),
            'specificationValues' => $product->specifications
                ->pluck('pivot.value', 'id')
                ->all(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);

        $product = DB::transaction(function () use ($request, $product) {
            $validatedData = $request->safe()->except([
                'specifications',
            ]);

            $product->update($validatedData);

            $this->syncSpecifications($product, $request->validated('specifications') ?? []);

            return $product->refresh();
        });

        toast_success('update');

        return to_route('dashboard.products.show', $product);
    }

    public function destroy(Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);

        $product->delete();

        toast_success('delete');

        return to_route('dashboard.products.index');
    }

    private function syncSpecifications(Product $product, array $specifications): void
    {
        $syncData = collect($specifications)
            ->filter(fn (?string $value): bool => filled($value))
            ->mapWithKeys(fn (string $value, int|string $id): array => [
                (int) $id => ['value' => trim($value)],
            ])
            ->all();

        $product->specifications()->sync($syncData);
    }
}
