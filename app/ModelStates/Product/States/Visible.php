<?php

namespace App\ModelStates\Product\States;

use App\ModelStates\Product\ProductState;

class Visible extends ProductState
{
    protected static string $name = 'visible';

    public function getUiClasses(): string
    {
        return 'pill pill-green';
    }
}
