<?php

namespace App\ModelStates\Category\States;

use App\ModelStates\Category\CategoryState;

class Hidden extends CategoryState
{
    protected static string $name = 'hidden';

    public function getUiClasses(): string
    {
        return 'pill pill-slate';
    }
}
