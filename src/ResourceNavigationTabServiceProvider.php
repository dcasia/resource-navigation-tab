<?php

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ResourceNavigationTabServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(static function (ServingNova $event) {
            Nova::script('resource-navigation-tab', __DIR__ . '/../dist/js/card.js');
        });
    }
}
