<?php

namespace App\ModelStates\Category\States;

use App\ModelStates\Category\CategoryState;

class Visible extends CategoryState
{
    protected static string $name = 'visible';

    public function getUiClasses(): string
    {
        return 'pill pill-green';
    }
}
