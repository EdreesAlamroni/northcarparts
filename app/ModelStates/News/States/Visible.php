<?php

namespace App\ModelStates\News\States;

use App\ModelStates\News\NewsState;

class Visible extends NewsState
{
    protected static string $name = 'visible';

    public function getUiClasses(): string
    {
        return 'pill pill-green';
    }
}
