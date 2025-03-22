<?php

namespace App\Providers;

use App\Models\MatchResult;
use App\Models\SetResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        Model::preventSilentlyDiscardingAttributes(!$this->app->isProduction());

        Relation::enforceMorphMap([
            'match' => MatchResult::class,
            'set'   => SetResult::class,
        ]);
    }
}
