<?php

namespace App\Concerns;

use BackedEnum;
use Illuminate\Support\Collection;

/**
 * @mixin BackedEnum
 */
trait EnumUtilities
{
    abstract protected function getTranslationKey(): string;

    public function label(): string
    {
        return __(sprintf(
            'app.enums.%s.%s',
            $this->getTranslationKey(),
            $this->value,
        ));
    }

    public function toOption(): object
    {
        return (object) [
            'id' => $this->value,
            'name' => $this->label(),
            'key' => $this->name,
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->value,
            'name' => $this->label(),
            'key' => $this->name,
        ];
    }

    public static function options(): Collection
    {
        return collect(self::cases())->map(function (self $case): object {
            return $case->toOption();
        });
    }
}
