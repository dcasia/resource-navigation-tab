<?php

declare(strict_types = 1);

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Support\Collection;
use Laravel\Nova\Element;
use Laravel\Nova\Exceptions\HelperNotSupported;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

/**
 * @mixin Resource
 */
trait HasResourceNavigationTabTrait
{
    /**
     * @throws HelperNotSupported
     */
    public function availableCardsForDetail(NovaRequest $request): Collection
    {
        $cards = parent::availableCardsForDetail($request);

        $method = match (true) {
            method_exists($this, 'fieldsForDetail') => 'fieldsForDetail',
            method_exists($this, 'fields') => 'fields',
        };

        $fields = collect($this->{$method}($request))->whereInstanceOf(ResourceNavigationField::class);

        /**
         * If no ResourceNavigationField is defined, return all cards, a.k.a do nothing
         */
        if ($fields->isEmpty()) {
            return $cards;
        }

        $activeTab = $fields->first(fn (ResourceNavigationField $field) => $field->isActive());
        $activeTab ??= $fields->first();

        return $cards
            ->filter(function (Element $card) use ($activeTab) {

                /**
                 * If user did not call ->withCards() default to show all cards
                 */
                if (is_null($activeTab->cards)) {
                    return true;
                }

                return in_array($card::class, $activeTab->cards);

            })
            ->prepend(new ResourceNavigationCard($fields))
            ->values();
    }
}
