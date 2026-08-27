<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\FilterType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreProductRequest;
use App\Http\Requests\Dashboard\UpdateProductRequest;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Services\ProductQrCodeGenerator;
use App\Support\GroupedSpecifications;
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
                'name',
                'state',
                'created_at',
            ])
            ->with(['category:id,name'])
            ->allowedFilters(
                'code',
                'slug',
                'name',
            )
            ->latest()
            ->paginate()
            ->withQueryString()
            ->appends($request->query());

        return view('pages.dashboard.products.index', [
            'products' => $products,
        ]);
    }

    public function create(GroupedSpecifications $groupedSpecifications): View
    {
        Gate::authorize('create', Product::class);

        return view('pages.dashboard.products.create', [
            'categories' => Category::list(),
            'manufacturers' => Manufacturer::list(),
            'states' => Product::getStateOptions(),
            'filterTypes' => FilterType::options(),
            'groupedSpecifications' => $groupedSpecifications->all(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Gate::authorize('create', Product::class);

        $product = DB::transaction(function () use ($request) {
            $validatedData = $request->safe()->except([
                'image',
                'specification_groups',
                'specification_value_ids',
            ]);

            /** @var Product $product */
            $product = Product::create($validatedData);

            if ($request->hasFile('image')) {
                $product->addMediaFromRequest('image')->toMediaCollection('image');
            }

            $product->specificationValues()->sync(
                $request->validated('specification_value_ids') ?? [],
            );

            app(ProductQrCodeGenerator::class)->regenerate($product);

            return $product;
        });

        toast_success('create');

        return to_route('dashboard.products.show', $product);
    }

    public function show(Product $product, GroupedSpecifications $groupedSpecifications): View
    {
        Gate::authorize('view', $product);

        $product->load([
            'category:id,name',
            'manufacturer:id,name',
            'specificationValues.specification:id,name',
        ]);

        return view('pages.dashboard.products.show', [
            'product' => $product,
            'groupedSpecifications' => $groupedSpecifications->forProduct($product),
        ]);
    }

    public function edit(Product $product, GroupedSpecifications $groupedSpecifications): View
    {
        Gate::authorize('update', $product);

        $product->load(['specificationValues:id,specification_id,value']);

        return view('pages.dashboard.products.edit', [
            'product' => $product,
            'categories' => Category::list(),
            'manufacturers' => Manufacturer::list(),
            'states' => Product::getStateOptions(),
            'filterTypes' => FilterType::options(),
            'groupedSpecifications' => $groupedSpecifications->all(),
            'selectedGroups' => $product->specificationValues
                ->pluck('specification_id')
                ->unique()
                ->values()
                ->all(),
            'selectedValueIds' => $product->specificationValues
                ->pluck('id')
                ->all(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);

        $product = DB::transaction(function () use ($request, $product) {
            $validatedData = $request->safe()->except([
                'specification_groups',
                'specification_value_ids',
            ]);

            $product->update($validatedData);

            $product->specificationValues()->sync(
                $request->validated('specification_value_ids') ?? [],
            );

            app(ProductQrCodeGenerator::class)->regenerate($product);

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
}
