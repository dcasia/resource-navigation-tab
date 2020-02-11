<?php

namespace DigitalCreative\ResourceNavigationTab;

use JsonSerializable;

class Resource implements JsonSerializable
{
    /**
     * @var ResourceNavigationTab
     */
    private $field;

    /**
     * NavigationResource constructor.
     *
     * @param ResourceNavigationTab $field
     */
    public function __construct(ResourceNavigationTab $field)
    {
        $this->field = $field;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'label' => $this->field->name,
            'slug' => $this->field->getResourceSlug(),
            'resourceId' => $this->field->getResourceId()
        ];
    }
}
