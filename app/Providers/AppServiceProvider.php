<?php

namespace App\Providers;
use App\Models\MaterialInsumo;
use App\Observers\MaterialInsumoObserver;
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
            MaterialInsumo::observe(MaterialInsumoObserver::class);
    }
}
