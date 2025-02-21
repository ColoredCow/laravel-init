<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
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
    public function boot(GateContract $gate): void
    {
        // Use the injected GateContract instance
        $gate->after(function ($user) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
