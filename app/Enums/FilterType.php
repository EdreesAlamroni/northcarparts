<?php

namespace App\Enums;

use App\Concerns\EnumUtilities;

enum FilterType: string
{
    use EnumUtilities;

    case SpinOnOilFilter = 'spin_on_oil_filter';
    case EcoOilFilter = 'eco_oil_filter';
    case AirFilter = 'air_filter';

    protected function getTranslationKey(): string
    {
        return 'filter_types';
    }
}
