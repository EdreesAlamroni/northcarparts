<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreUserRequest;
use App\Http\Requests\Dashboard\UpdateUserRequest;
use App\Models\User;
use App\Support\GroupedRoles;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $users = QueryBuilder::for(User::class)
            ->select([
                'id',
                'uuid',
                'name',
                'email',
                'created_at',
            ])
            ->allowedFilters(
                'name',
                'email',
            )
            ->paginate()
            ->withQueryString()
            ->appends($request->query());

        return view('pages.dashboard.users.index', [
            'users' => $users,
        ]);
    }

    public function create(GroupedRoles $groupedRoles): View
    {
        Gate::authorize('create', User::class);

        return view('pages.dashboard.users.create', [
            'groupedRoles' => $groupedRoles->all(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $user = DB::transaction(function () use ($request) {
            $validated = $request->safe()->except(['password_confirmation', 'roles']);

            $user = User::create($validated);

            $user->assignRole($request->validated('roles'));

            return $user;
        });

        toast_success('create');

        return to_route('dashboard.users.show', $user);
    }

    public function show(User $user, GroupedRoles $groupedRoles): View
    {
        Gate::authorize('view', $user);

        return view('pages.dashboard.users.show', [
            'user' => $user,
            'groupedRoles' => $groupedRoles->forUser($user),
        ]);
    }

    public function edit(User $user, GroupedRoles $groupedRoles): View
    {
        Gate::authorize('update', $user);

        return view('pages.dashboard.users.edit', [
            'user' => $user,
            'groupedRoles' => $groupedRoles->all(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $user = DB::transaction(function () use ($request, $user) {
            $user->update($request->safe()->except(['roles']));

            $user->syncRoles($request->validated('roles'));

            return $user->refresh();
        });

        toast_success('update');

        return to_route('dashboard.users.show', $user);
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        toast_success('delete');

        return to_route('dashboard.users.index');
    }
}
