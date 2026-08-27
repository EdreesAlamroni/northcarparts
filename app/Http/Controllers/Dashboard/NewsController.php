<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreNewsRequest;
use App\Http\Requests\Dashboard\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', News::class);

        $news = QueryBuilder::for(News::class)
            ->select([
                'id',
                'uuid',
                'title',
                'published_at',
                'state',
                'created_at',
            ])
            ->allowedFilters(
                'title',
                'published_at',
            )
            ->latest()
            ->paginate()
            ->withQueryString()
            ->appends($request->query());

        return view('pages.dashboard.news.index', [
            'news' => $news,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', News::class);

        return view('pages.dashboard.news.create', [
            'states' => News::getStateOptions(),
        ]);
    }

    public function store(StoreNewsRequest $request): RedirectResponse
    {
        Gate::authorize('create', News::class);

        $news = DB::transaction(function () use ($request) {
            $validatedData = $request->safe()->except(['image']);

            /** @var News $news */
            $news = News::create($validatedData);

            if ($request->hasFile('image')) {
                $news->addMediaFromRequest('image')->toMediaCollection('image');
            }

            return $news;
        });

        toast_success('create');

        return to_route('dashboard.news.show', $news);
    }

    public function show(News $news): View
    {
        Gate::authorize('view', $news);

        return view('pages.dashboard.news.show', [
            'news' => $news,
        ]);
    }

    public function edit(News $news): View
    {
        Gate::authorize('update', $news);

        return view('pages.dashboard.news.edit', [
            'news' => $news,
            'states' => News::getStateOptions(),
        ]);
    }

    public function update(UpdateNewsRequest $request, News $news): RedirectResponse
    {
        Gate::authorize('update', $news);

        $news = DB::transaction(function () use ($request, $news) {
            $news->update($request->validated());

            return $news->refresh();
        });

        toast_success('update');

        return to_route('dashboard.news.show', $news);
    }

    public function destroy(News $news): RedirectResponse
    {
        Gate::authorize('delete', $news);

        $news->delete();

        toast_success('delete');

        return to_route('dashboard.news.index');
    }
}
