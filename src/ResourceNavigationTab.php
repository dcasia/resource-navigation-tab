<?php

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Support\Str;
use Laravel\Nova\AuthorizedToSee;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Panel;
use Laravel\Nova\ProxiesCanSeeToGate;

class ResourceNavigationTab extends Panel
{

    use ProxiesCanSeeToGate;
    use AuthorizedToSee;

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

        $this->resourceLabel = $name;
        $this->name = $name;
        $this->id = Str::slug($name);
        $this->data = is_callable($fields) ? $fields() : $fields;

        /**
         * Proxy canSee on all children
         *
         * @var Field $field
         */
        foreach ($this->data as $field) {

            $originalCallback = $field->seeCallback;

            $field->seeCallback = function (...$arguments) use ($originalCallback) {

                $selfIsCallable = is_callable($this->seeCallback);
                $originalIsCallable = is_callable($originalCallback);

                if ($selfIsCallable && $originalIsCallable) {

                    return call_user_func($this->seeCallback, ...$arguments) && $originalCallback(...$arguments);

                }

                if ($selfIsCallable) {

                    return call_user_func($this->seeCallback, ...$arguments);

                }

                if ($originalIsCallable) {

                    return $originalCallback(...$arguments);

                }

                return true;

            };

        }

    }

    /**
     * Create a new element.
     *
     * @param array $arguments
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * @return void
     */
    public function behaveAsPanel(): void
    {
        $this->data = $this->prepareFields($this->data);
    }

    /**
     * @param string|null $activeTab
     *
     * @return bool
     */
    public function isActive(?string $activeTab): bool
    {
        return $this->id === $activeTab;
    }

    /**
     * @return $this
     */
    public function withoutCards(): self
    {
        $this->withCards = false;

        return $this;
    }

    /**
     * @param string ...$cards
     *
     * @return $this
     */
    public function removeCards(...$cards): self
    {
        $this->cardsToRemove = $cards;

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function resourceTableTitle(string $label): self
    {
        $this->resourceLabel = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getTableLabel(): string
    {
        return $this->resourceLabel ?? $this->name;
    }

}
