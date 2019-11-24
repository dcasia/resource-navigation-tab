<?php

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Support\Str;
use Laravel\Nova\Panel;

class ResourceNavigationTab extends Panel
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'navigation-field';

    /**
     * @var string|null
     */
    public $resourceLabel = null;

    /**
     * @var array
     */
    public $cardsToRemove = [];

    /**
     * @var boolean
     */
    public $withCards = true;

    /**
     * @var string
     */
    public $id;

    /**
     * Create a new field.
     *
     * @param string $name
     * @param array|callable $fields
     */
    public function __construct(string $name, $fields)
    {
        $this->name = $name;
        $this->id = Str::slug($name);
        $this->data = is_callable($fields) ? call_user_func($fields) : $fields;
    }

    /**
     * Create a new element.
     *
     * @param array $arguments
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    public function behaveAsPanel()
    {
        $this->data = $this->prepareFields($this->data);
    }

    public function isActive(?string $activeTab): bool
    {
        return Str::slug($this->name) === $activeTab;
    }

    public function withoutCards(): self
    {
        $this->withCards = false;

        return $this;
    }

    public function removeCards(...$cards): self
    {
        $this->cardsToRemove = $cards;

        return $this;
    }

    public function resourceTableTitle(string $label): self
    {
        $this->resourceLabel = $label;

        return $this;
    }

    public function getTableLabel(): string
    {
        return $this->resourceLabel ?? $this->name;
    }

}
