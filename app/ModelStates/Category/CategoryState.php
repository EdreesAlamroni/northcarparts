<?php

namespace App\ModelStates\Category;

use App\ModelStates\Category\States\Hidden;
use App\ModelStates\Category\States\Visible;
use App\ModelStates\ModelState;
use Spatie\ModelStates\StateConfig;

abstract class CategoryState extends ModelState
{
    abstract public function getUiClasses(): string;

    protected static function getTranslationKey(): string
    {
        return 'category.state';
    }

    public static function config(): StateConfig
    {
        return parent::config()
            ->registerState([Visible::class, Hidden::class])
            ->default(Visible::class)
            ->allowTransition(Hidden::class, Visible::class)
            ->allowTransition(Visible::class, Hidden::class);
    }
}
