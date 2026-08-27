<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('news:view-any');
    }

    public function view(User $user, News $news): bool
    {
        return $user->can('news:view');
    }

    public function create(User $user): bool
    {
        return $user->can('news:create');
    }

    public function update(User $user, News $news): bool
    {
        return $user->can('news:update');
    }

    public function delete(User $user, News $news): bool
    {
        return $user->can('news:delete');
    }

    public function stateUpdate(User $user, News $news): bool
    {
        if ($news->getTransitionableStates('state')->isEmpty()) {
            return false;
        }

        return $user->can('news:update');
    }
}
