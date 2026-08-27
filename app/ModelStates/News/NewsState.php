<?php

namespace App\ModelStates\News;

use App\ModelStates\ModelState;
use App\ModelStates\News\States\Hidden;
use App\ModelStates\News\States\Visible;
use Spatie\ModelStates\StateConfig;

abstract class NewsState extends ModelState
{
    abstract public function getUiClasses(): string;

    protected static function getTranslationKey(): string
    {
        return 'news.state';
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
