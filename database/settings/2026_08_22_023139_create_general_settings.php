<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.company_name', 'North Car Parts');
        $this->migrator->add('general.address', null);
        $this->migrator->add('general.phone_number', null);
        $this->migrator->add('general.email', null);
    }
};
