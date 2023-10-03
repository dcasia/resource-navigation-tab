<?php

declare(strict_types = 1);

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ResourceNavigationTabServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Nova::serving(function (ServingNova $event): void {

            Nova::script('resource-navigation-tab', __DIR__ . '/../dist/js/card.js');
            Nova::style('resource-navigation-tab', __DIR__ . '/../dist/css/card.css');

        });

        $this->app->terminating(function (): void {
            ResourceNavigationField::$active = null;
        });
    }
}
