<?php

namespace App\Providers;

use App\Models\SetConfig;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);

        // Make the configured site logo available to every view as $siteLogo.
        View::share('siteLogo', $this->siteLogo());

        // Super Admin bypasses every permission check (menu, routes, @can) —
        // no need to assign it every individual permission by hand.
        Gate::before(fn ($user, string $ability) => $user->hasRole('Super Admin') ? true : null);
    }

    /**
     * Resolve the site logo URL from set_config, falling back to the
     * default asset when the table isn't migrated yet (fresh installs).
     */
    protected function siteLogo(): string
    {
        try {
            return asset(SetConfig::get('SITELOGO', 'assets/images/logo.png'));
        } catch (\Throwable $e) {
            return asset('assets/images/logo.png');
        }
    }
}
