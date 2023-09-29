<?php

declare(strict_types=1);

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class ResourceNavigationField extends Panel
{
    public ?array $cards = null;

    public static ?string $active = null;

    public function __construct(string $name, array $fields = [])
    {
        $this->name = $name;
    }

    public function fields(array|callable $fields): static
    {
        $this->data = $this->prepareFields($fields);

        return $this;
    }

    public function authorizedFields(): Collection
    {
        return collect($this->data)->filter(fn(Field $field) => $field->authorize($this->request()));
    }

    public function getSlug(): string
    {
        return Str::slug($this->name);
    }

    public function isActive(): bool
    {
        $activeSlug = $this->request()->input(ResourceNavigationTabServiceProvider::$COOKIE_NAME);

        if (is_null($activeSlug) && is_null(static::$active) && $this->authorizedFields()->isNotEmpty()) {
            $activeSlug = static::$active = $this->getSlug();
        }

        if ($activeSlug === $this->getSlug()) {
            return true;
        }

        return false;
    }

    public function withCards(array $cards): static
    {
        $this->cards = $cards;

        return $this;
    }

    public function withoutCards(): static
    {
        $this->cards = [];

        return $this;
    }

    protected function prepareFields($fields): array
    {
        /**
         * If we are not in a detail page, we can treat this as a normal panel
         */
        if (!$this->request()->isResourceDetailRequest()) {
            return parent::prepareFields($fields);
        }

        $this->data = parent::prepareFields($fields);

        /**
         * If the tab is not active, we pretend there are no fields in it
         */
        if ($this->isActive() === false) {
            return $this->data = [];
        }

        return $this->data;
    }

    private function request(): NovaRequest
    {
        return resolve(NovaRequest::class);
    }
}
