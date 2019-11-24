# Resource Navigation Tab

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digital-creative/resource-navigation-tab)](https://packagist.org/packages/digital-creative/resource-navigation-tab)
[![Total Downloads](https://img.shields.io/packagist/dt/digital-creative/resource-navigation-tab)](https://packagist.org/packages/digital-creative/resource-navigation-tab)
[![License](https://img.shields.io/packagist/l/digital-creative/resource-navigation-tab)](https://github.com/dcasia/resource-navigation-tab/blob/master/LICENSE)

Organize your long pile of tables and relationships into structured pages.

![PillFilter in Action](https://raw.githubusercontent.com/dcasia/resource-navigation-tab/master/screenshots/demo-1.png)

# Installation

You can install the package via composer:

```
composer require digital-creative/resource-navigation-tab
```

## Basic Usage

Firstly import `HasResourceNavigationTabTrait` trait into your resource 
and start grouping your fields with `ResourceNavigationTab` object:

```php
use DigitalCreative\NavigationCard\HasResourceNavigationTabTrait;
use DigitalCreative\NavigationCard\ResourceNavigationTab;

class ExampleNovaResource extends Resource {
 
    use HasResourceNavigationTabTrait; // Important!!

    public function fields(Request $request)
    {
        return [
            ResourceNavigationTab::make('Profile', [
                Text::make('Name'),
                Text::make('Age'),
                HasMany::make('Hobbies')
            ])
            ResourceNavigationTab::make('Activities', [...]),
            ResourceNavigationTab::make('Preferences', [...]),
            ResourceNavigationTab::make('Purchased Products', [...]),
        ];
    }

}
```

By default the main resource table (the one with the edit/delete buttons) will have the same title as your tabs,
however you can customize it by calling `->resourceTableTitle('Another title')`

```php
public function fields(Request $request)
{
    return [
        ResourceNavigationTab::make('Tab Title', [...])->resourceTableTitle('Resource Table Title'),
    ];
}
```

Every defined card will be shown on every tab by default, 
however you can choose which card you want to show when a specific tab is selected:

```php
use DigitalCreative\NavigationCard\HasResourceNavigationTabTrait;
use DigitalCreative\NavigationCard\ResourceNavigationTab;

class ExampleNovaResource extends Resource {
 
    public function fields(Request $request)
    {
        return [
            ResourceNavigationTab::make('Profile', []), // show all the availiable cards by default
            ResourceNavigationTab::make('Activities', [...])->removeCards([ ClientProfileCard::class, ... ]), // remove only the specified card from this tab
            ResourceNavigationTab::make('Preferences', [...])->withoutCards(), // removes all cards when this tab is active
        ];
    }

    public function cards(Request $request)
    {
        return [
            new ClientPerformaceCard(),
            new DailySalesCard(),
            new ClientProfileCard()
        ];
    }

}
```

## License

The MIT License (MIT). Please see [License File](https://raw.githubusercontent.com/dcasia/resource-navigation-tab/master/LICENSE) for more information.
