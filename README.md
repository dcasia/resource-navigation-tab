# Resource Navigation Tab

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digital-creative/resource-navigation-tab)](https://packagist.org/packages/digital-creative/resource-navigation-tab)
[![Total Downloads](https://img.shields.io/packagist/dt/digital-creative/resource-navigation-tab)](https://packagist.org/packages/digital-creative/resource-navigation-tab)
[![License](https://img.shields.io/packagist/l/digital-creative/resource-navigation-tab)](https://github.com/dcasia/resource-navigation-tab/blob/master/LICENSE)

Organize your long pile of tables and relationships into structured pages.

![Resource Navigation Tab in Action](https://raw.githubusercontent.com/dcasia/resource-navigation-tab/master/screenshots/demo-1.png)

# Installation

You can install the package via composer:

```
composer require digital-creative/resource-navigation-tab
```

## Basic Usage

First, import `HasResourceNavigationTabTrait` trait into your resource 
and start grouping your fields with the `ResourceNavigationTab` object:

```php
use DigitalCreative\ResourceNavigationTab\HasResourceNavigationTabTrait;
use DigitalCreative\ResourceNavigationTab\ResourceNavigationTab;

class ExampleNovaResource extends Resource {
 
    use HasResourceNavigationTabTrait; // Important!!

    public function fields(Request $request)
    {
        return [
            ResourceNavigationTab::make([
                'label' => 'Information',
                'behaveAsPanel' => true / false,
                'fields' => [
                    Text::make('Name'),
                    Text::make('Age'),
                    HasMany::make('Hobbies')
                ]
            ]),
            ResourceNavigationTab::make([ 'label' => 'Activities' ]),
            ResourceNavigationTab::make([ 'label' => 'Social Interactions' ]),
            ResourceNavigationTab::make([ 'label' => 'Settings' ]),
        ];
    }

}
```

Once setup navigate to your resource detail view, and you should be presented with this card:

![Resource Navigation Tab in Action](https://raw.githubusercontent.com/dcasia/resource-navigation-tab/master/screenshots/demo-3.png)

By default the main resource table (the one with the edit/delete buttons) will have the same title as your tabs,
however you can customize it by calling `->resourceTableTitle('Another title')`

```php
public function fields(Request $request)
{
    return [
        ResourceNavigationTab::make([
            'label' => 'Tab Title', 
            'resourceTableTitle' => 'Resource Table Title'
            'fields' => [...]
        ]),
    ];
}
```

Every defined card will be shown on every tab by default, 
however you can choose which card you want to show when a specific tab is selected:

```php
use DigitalCreative\ResourceNavigationTab\HasResourceNavigationTabTrait;
use DigitalCreative\ResourceNavigationTab\ResourceNavigationTab;
use DigitalCreative\ResourceNavigationTab\CardMode;

class ExampleNovaResource extends Resource {
 
    public function fields(Request $request)
    {
        return [
            ResourceNavigationTab::make([ 'label' => 'Profile' ]), // show all the available cards by default
            ResourceNavigationTab::make([ 
                'label' => 'Activities',
                'cardMode' => CardMode::KEEP_ALL | CardMode::EXCLUDE_ALL // show all or remove all cards when this tab is active
            ]),
            ResourceNavigationTab::make([ 
                'label' => 'Preferences',
                'cardMode' => CardMode::ONLY | CardMode::EXCEPT // show or remove only the selected cards
                'cards' => [
                   ClientPerformanceCard::class,
                   ClientProfileCard::class,
                ]
            ]), 
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

The MIT License (MIT). Please see [License File](https://raw.githubusercontent.com/dcasia/resource-navigation-tab/master/LICENSE) for more information.
