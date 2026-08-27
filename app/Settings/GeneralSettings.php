<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $company_name;

    public ?string $address;

    public ?string $phone_number;

    public ?string $email;

    public static function group(): string
    {
        return 'general';
    }
}
