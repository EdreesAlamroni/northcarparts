<?php

namespace App\ModelStates\Product\States;

use App\ModelStates\Product\ProductState;

class Hidden extends ProductState
{
    protected static string $name = 'hidden';

    public function getUiClasses(): string
    {
        return 'pill pill-slate';
    }
}
