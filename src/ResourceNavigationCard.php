<?php

namespace DigitalCreative\ResourceNavigationTab;

use Laravel\Nova\Card;

class ResourceNavigationCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/2';

    /**
     * NavigationCard constructor.
     *
     * @param $resources
     */
    public function __construct($resources)
    {

        $this->withMeta([
            'resources' => $resources
        ]);

        $this->onlyOnDetail();

    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'resource-navigation-card';
    }
}
