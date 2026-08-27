<?php

namespace App\ModelStates\Product;

use App\ModelStates\ModelState;
use App\ModelStates\Product\States\Hidden;
use App\ModelStates\Product\States\Visible;
use Spatie\ModelStates\StateConfig;

abstract class ProductState extends ModelState
{
    abstract public function getUiClasses(): string;

    protected static function getTranslationKey(): string
    {
        return 'product.state';
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
