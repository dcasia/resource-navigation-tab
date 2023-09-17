<?php

declare(strict_types = 1);

namespace DigitalCreative\ResourceNavigationTab;

use App\Http\Middleware\EncryptCookies;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ResourceNavigationTabServiceProvider extends ServiceProvider
{
    public static string $COOKIE_NAME = 'x-resource-navigation-tab';

    public function boot(): void
    {
        Nova::serving(static function (ServingNova $event): void {

            Nova::script('resource-navigation-tab', __DIR__ . '/../dist/js/card.js');
            Nova::style('resource-navigation-tab', __DIR__ . '/../dist/css/card.css');

        });

        $this->app->afterResolving(EncryptCookies::class, function (EncryptCookies $middleware): void {
            $middleware->disableFor(ResourceNavigationTabServiceProvider::$COOKIE_NAME);
        });
    }
}
