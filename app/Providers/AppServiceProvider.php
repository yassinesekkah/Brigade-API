<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Recommendation;
use App\Policies\RecommendationPolicy;
use Illuminate\Support\Facades\Gate;

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
        Gate::policy(Recommendation::class, RecommendationPolicy::class);
    }
}
