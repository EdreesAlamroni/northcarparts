<?php

namespace App\Providers;

use App\Authorization\Settings;
use App\Policies\SettingsPolicy;
use App\Settings\GeneralSettings;
use App\Settings\SocialSettings;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Settings::class, SettingsPolicy::class);

        $this->configureDefaults();
        $this->configureWebsiteViews();
    }

    protected function configureWebsiteViews(): void
    {
        View::composer(['layouts.website', 'pages.website.*'], function ($view): void {
            app()->setLocale('ar');

            $view->with([
                'generalSettings' => app(GeneralSettings::class),
                'socialSettings' => app(SocialSettings::class),
            ]);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
