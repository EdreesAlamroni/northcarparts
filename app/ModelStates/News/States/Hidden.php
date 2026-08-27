<?php

namespace App\ModelStates\News\States;

use App\ModelStates\News\NewsState;

class Hidden extends NewsState
{
    protected static string $name = 'hidden';

    public function getUiClasses(): string
    {
        return 'pill pill-slate';
    }
}
