<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreCategoryRequest;
use App\Http\Requests\Dashboard\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Category::class);

        $categories = QueryBuilder::for(Category::class)
            ->select([
                'id',
                'uuid',
                'name',
                'sort_order',
                'state',
                'created_at',
            ])
            ->allowedFilters(
                'name',
            )
            ->orderBy('sort_order')
            ->paginate()
            ->withQueryString()
            ->appends($request->query());

        return view('pages.dashboard.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Category::class);

        return view('pages.dashboard.categories.create', [
            'states' => Category::getStateOptions(),
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $category = DB::transaction(function () use ($request) {
            $validatedData = $request->safe()->except(['image']);

            /** @var Category $category */
            $category = Category::create($validatedData);

            if ($request->hasFile('image')) {
                $category->addMediaFromRequest('image')->toMediaCollection('image');
            }

            return $category;
        });

        toast_success('create');

        return to_route('dashboard.categories.show', $category);
    }

    public function show(Category $category): View
    {
        Gate::authorize('view', $category);

        return view('pages.dashboard.categories.show', [
            'category' => $category,
        ]);
    }

    public function edit(Category $category): View
    {
        Gate::authorize('update', $category);

        return view('pages.dashboard.categories.edit', [
            'category' => $category,
            'states' => Category::getStateOptions(),
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);

        DB::transaction(function () use ($request, $category): void {
            $category->update($request->validated());
        });

        toast_success('update');

        return to_route('dashboard.categories.show', $category);
    }

    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('delete', $category);

        $category->delete();

        toast_success('delete');

        return to_route('dashboard.categories.index');
    }
}
