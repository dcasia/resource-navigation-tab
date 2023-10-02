<?php

declare(strict_types = 1);

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Support\Collection;
use Laravel\Nova\Card;

class ResourceNavigationCard extends Card
{
    public $width = 'full';
    public $onlyOnDetail = true;

    public function __construct(
        private readonly Collection $resources,
    )
    {
    }

    public function component(): string
    {
        return 'resource-navigation-card';
    }

    private function resolveResources(): array
    {
        $resources = $this->resources
            ->filter(fn (ResourceNavigationField $resource) => $resource->authorizedFields()->isNotEmpty())
            ->map(fn (ResourceNavigationField $resource) => [
                'name' => $resource->name,
                'isActive' => $resource->isActive(),
                'slug' => $resource->getSlug(),
            ])
            ->values()
            ->toArray();

        /**
         * If no resource is active, activate the first one
         */
        if (blank(array_filter($resources, fn (array $resource) => $resource[ 'isActive' ]))) {
            $resources[ 0 ][ 'isActive' ] = true;
        }

        return $resources;
    }

    public function jsonSerialize(): array
    {
        return array_merge([ 'resources' => $this->resolveResources() ], parent::jsonSerialize());
    }
}
