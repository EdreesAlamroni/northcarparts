<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Product\CreateProduct;
use App\Actions\Product\UpdateProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreProductRequest;
use App\Http\Requests\Dashboard\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Specification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
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
                'oem_number',
                'state',
                'created_at',
            ])
            ->with(['category:id,name'])
            ->allowedFilters(
                'code',
                AllowedFilter::exact('category_id'),
            )
            ->latest()
            ->paginate()
            ->withQueryString()
            ->appends($request->query());

        return view('pages.dashboard.products.index', [
            'products' => $products,
            'categories' => Category::list(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Product::class);

        return view('pages.dashboard.products.create', [
            'categories' => Category::list(),
            'manufacturers' => Manufacturer::list(),
            'brands' => Brand::list(),
            'states' => Product::getStateOptions(),
            'specifications' => Specification::list(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Gate::authorize('create', Product::class);

        $product = app(CreateProduct::class)->execute(
            $request->getAttributes(),
            $request->validated('specifications', []),
            $request->validated('cross_references', []),
            array_filter($request->file('images', [])),
        );

        toast_success('create');

        return to_route('dashboard.products.show', $product);
    }

    public function show(Product $product): View
    {
        Gate::authorize('view', $product);

        $product->load([
            'category',
            'manufacturer',
            'specifications',
            'crossReferences',
            'crossReferences.brand',
        ]);

        return view('pages.dashboard.products.show', [
            'product' => $product,
            'specifications' => $product->specifications->sortBy('name')->values(),
            'crossReferences' => $product->crossReferences
                ->sortBy('brand.name')
                ->values(),
        ]);
    }

    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        $product->load([
            'specifications',
            'crossReferences',
        ]);

        $specificationValues = $product->specifications->pluck('pivot.value', 'id')->all();
        $crossReferenceValues = $product->crossReferences->pluck('reference_code', 'brand_id')->all();

        return view('pages.dashboard.products.edit', [
            'product' => $product,
            'categories' => Category::list(),
            'manufacturers' => Manufacturer::list(),
            'brands' => Brand::list(),
            'specifications' => Specification::list(),
            'specificationValues' => $specificationValues,
            'crossReferenceValues' => $crossReferenceValues,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);

        $product = app(UpdateProduct::class)->execute(
            $product,
            $request->getAttributes(),
            $request->validated('specifications', []),
            $request->validated('cross_references', []),
        );

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
