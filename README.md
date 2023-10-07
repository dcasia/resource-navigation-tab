# Resource Navigation Tab

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digital-creative/resource-navigation-tab)](https://packagist.org/packages/digital-creative/resource-navigation-tab)
[![Total Downloads](https://img.shields.io/packagist/dt/digital-creative/resource-navigation-tab)](https://packagist.org/packages/digital-creative/resource-navigation-tab)
[![License](https://img.shields.io/packagist/l/digital-creative/resource-navigation-tab)](https://github.com/dcasia/resource-navigation-tab/blob/master/LICENSE)

Organize your resource fields into tabs.

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/dcasia/resource-navigation-tab/main/screenshots/dark/demo-2.png">
  <img alt="Resource Navigation Tab in Action" src="https://raw.githubusercontent.com/dcasia/resource-navigation-tab/main/screenshots/light/demo-2.png">
</picture>

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

    public function fields(NovaRequest $request): array
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

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/dcasia/resource-navigation-tab/main/screenshots/dark/demo-1.png">
  <img alt="Resource Navigation Tab in Action" src="https://raw.githubusercontent.com/dcasia/resource-navigation-tab/main/screenshots/light/demo-1.png">
</picture>

Every defined card will be shown on every tab by default, however you can choose which card you want to show when a
specific tab is selected:

```php
use DigitalCreative\ResourceNavigationTab\HasResourceNavigationTabTrait;
use DigitalCreative\ResourceNavigationTab\ResourceNavigationField;
use DigitalCreative\ResourceNavigationTab\CardMode;

class ExampleNovaResource extends Resource {

    use HasResourceNavigationTabTrait;
 
    public function fields(NovaRequest $request): array
    {
        return [
            ResourceNavigationField::make('Information'), // show all the available cards by default
            ResourceNavigationField::make('Activities')->withCards([ DailySalesCard::class, ClientProfileCard::class ]), // only show these cards when this tab is active
            ResourceNavigationField::make('Settings')->withoutCards(), // hide all cards when this tab is active
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [
            new ClientPerformanceCard(),
            new DailySalesCard(),
            new ClientProfileCard()
        ];
    }

}
```

## ⭐️ Show Your Support

Please give a ⭐️ if this project helped you!

### Other Packages You Might Like

- [Collapsible Resource Manager](https://github.com/dcasia/collapsible-resource-manager) - Provides an easy way to order and group your resources on the sidebar.
- [Resource Navigation Tab](https://github.com/dcasia/resource-navigation-tab) - Organize your resource fields into tabs.
- [Resource Navigation Link](https://github.com/dcasia/resource-navigation-link) - Create links to internal or external resources.
- [Nova Mega Filter](https://github.com/dcasia/nova-mega-filter) - Display all your filters in a card instead of a tiny dropdown!
- [Nova Pill Filter](https://github.com/dcasia/nova-pill-filter) - A Laravel Nova filter that renders into clickable pills.
- [Nova Slider Filter](https://github.com/dcasia/nova-slider-filter) - A Laravel Nova filter for picking range between a min/max value.
- [Nova Range Input Filter](https://github.com/dcasia/nova-range-input-filter) - A Laravel Nova range input filter.
- [Nova FilePond](https://github.com/dcasia/nova-filepond) - A Nova field for uploading File, Image and Video using Filepond.
- [Custom Relationship Field](https://github.com/dcasia/custom-relationship-field) - Emulate HasMany relationship without having a real relationship set between resources.
- [Column Toggler](https://github.com/dcasia/column-toggler) - A Laravel Nova package that allows you to hide/show columns in the index view.
- [Batch Edit Toolbar](https://github.com/dcasia/batch-edit-toolbar) - Allows you to update a single column of a resource all at once directly from the index page.

## License

The MIT License (MIT). Please see [License File](./LICENSE) for more information.
