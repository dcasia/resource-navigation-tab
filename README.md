# Resource Navigation Tab

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digital-creative/resource-navigation-tab)](https://packagist.org/packages/digital-creative/resource-navigation-tab)
[![Total Downloads](https://img.shields.io/packagist/dt/digital-creative/resource-navigation-tab)](https://packagist.org/packages/digital-creative/resource-navigation-tab)
[![License](https://img.shields.io/packagist/l/digital-creative/resource-navigation-tab)](https://github.com/dcasia/resource-navigation-tab/blob/master/LICENSE)

Organize your long pile of tables and relationships into structured pages.

![Resource Navigation Tab in Action](./screenshots/dark/demo-2.png#gh-dark-mode-only)
![Resource Navigation Tab in Action](./screenshots/light/demo-2.png#gh-light-mode-only)

# Installation

You can install the package via composer:

```shell
composer require digital-creative/resource-navigation-tab
```

## Basic Usage

First, import `HasResourceNavigationTabTrait` trait into your resource and start grouping your fields with
the `ResourceNavigationField` object:

```php
use DigitalCreative\ResourceNavigationTab\HasResourceNavigationTabTrait;
use DigitalCreative\ResourceNavigationTab\ResourceNavigationField;

class ExampleNovaResource extends Resource {
 
    use HasResourceNavigationTabTrait;

    public function fields(Request $request)
    {
        return [
            ResourceNavigationField::make('Information')
                ->fields([
                    Text::make('Name'),
                    Text::make('Age'),
                    HasMany::make('Hobbies'),
                ]),
            ResourceNavigationField::make('Activities')->fields([ ... ]),
            ResourceNavigationField::make('Social Interactions')->fields([ ... ]),
            ResourceNavigationField::make('Settings')->fields([ ... ]),
        ];
    }

}
```

Once setup navigate to your resource detail view, and you should be presented with this card:

![Resource Navigation Tab in Action](./screenshots/dark/demo-1.png#gh-dark-mode-only)
![Resource Navigation Tab in Action](./screenshots/light/demo-1.png#gh-light-mode-only)

Every defined card will be shown on every tab by default, however you can choose which card you want to show when a
specific tab is selected:

```php
use DigitalCreative\ResourceNavigationTab\HasResourceNavigationTabTrait;
use DigitalCreative\ResourceNavigationTab\ResourceNavigationField;
use DigitalCreative\ResourceNavigationTab\CardMode;

class ExampleNovaResource extends Resource {

    use HasResourceNavigationTabTrait;
 
    public function fields(Request $request)
    {
        return [
            ResourceNavigationField::make('Information'), // show all the available cards by default
            ResourceNavigationField::make('Activities')->withCards([ DailySalesCard::class, ClientProfileCard::class ]), // only show these cards when this tab is active
            ResourceNavigationField::make('Activities')->withoutCards([ ... ]), // hide all cards when this tab is active
        ];
    }

    public function cards(Request $request)
    {
        return [
            new ClientPerformanceCard(),
            new DailySalesCard(),
            new ClientProfileCard()
        ];
    }

}
```

## License

The MIT License (MIT). Please
see [License File](https://raw.githubusercontent.com/dcasia/resource-navigation-tab/master/LICENSE) for more
information.
