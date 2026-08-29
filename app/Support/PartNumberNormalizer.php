<?php

namespace App\Support;

use Illuminate\Support\Str;

class PartNumberNormalizer
{
    public static function normalize(string $value): string
    {
        $upper = Str::upper(trim($value));
        $normalizedValue = preg_replace('/[^A-Z0-9]+/', '', $upper);

        if (! is_string($normalizedValue)) {
            return '';
        }

        return $normalizedValue;
    }
}
