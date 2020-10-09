<?php

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\FieldCollection;
use Laravel\Nova\Http\Controllers\ResourceShowController;
use Laravel\Nova\Http\Requests\CardRequest;
use Laravel\Nova\Http\Requests\MetricRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Laravel\Nova\Resource as NovaResource;
use Throwable;

trait HasResourceNavigationTabTrait
{

    /**
     * Resolve the detail fields and assign them to their associated panel.
     *
     * @param NovaRequest $request
     * @param NovaResource $resource
     *
     * @throws Throwable
     * @return FieldCollection
     */
    public function detailFieldsWithinPanels(NovaRequest $request, NovaResource $resource)
    {
        $activeNavigationField = $this->getActiveNavigationField($request);

        return $this->assignToPanels(
            $activeNavigationField->getTabLabel($request), $this->detailFields($request)
        );
    }

    public function availablePanelsForDetail(NovaRequest $request, NovaResource $resource)
    {

        $label = Panel::defaultNameForDetail($resource);
        $panels = $this->panelsWithDefaultLabel($label, $request);

        $navigationField = $this->getActiveNavigationField($request);

        /**
         * Replace the name of the active panel with the same label as the tab
         */
        collect($panels)->firstWhere('name', $label)->name = $navigationField->getTabLabel($request);

        return $panels;

    }

    /**
     * Get the cards for the given request.
     *
     * @param NovaRequest $request
     *
     * @throws Throwable
     * @return Collection
     */
    public function resolveCards(NovaRequest $request)
    {

        $activeNavigationField = $this->getActiveNavigationField($request);
        $navigationResources = $this->resolveNavigationResources($request);

        $cards = collect($this->cards($request));
        $cardsToRemove = collect();

        $resources = $navigationResources
            ->each(static function (ResourceNavigationTab $field) use ($cardsToRemove, $cards) {

                $slug = $field->getResourceSlug();

                if ($field->cardMode->is(CardMode::EXCLUDE_ALL)) {

                    /**
                     * Mark all cards to be excluded
                     */
                    $cardsToRemove->put($slug, $cards->map(static function (Card $card) {
                        return get_class($card);
                    }));

                }

                if ($field->cardMode->is(CardMode::KEEP_ALL)) {

                    $cardsToRemove->put($slug, []);

                }

                if ($field->cardMode->is(CardMode::ONLY)) {

                    $keepCards = $field->getCards();

                    $cardsToRemove->put(
                        $slug,
                        $cards->filter(static function (Card $card) use ($keepCards) {
                            return !$keepCards->contains(get_class($card));
                        })->map(static function (Card $card) {
                            return get_class($card);
                        })->flatten()
                    );

                }

                if ($field->cardMode->is(CardMode::EXCEPT)) {

                    $cardsToRemove->put(
                        $slug,
                        $field->getCards()
                              ->map(static function (string $card) use ($cards) {
                                  return $cards->whereInstanceOf($card)->map(static function (Card $card) {
                                      return get_class($card);
                                  });
                              })
                              ->flatten()
                    );

                }

            })
            ->mapInto(Resource::class)
            ->values();

        /**
         * Remove all unnecessary cards
         */

        foreach ($this->getCardsToRemove($request, $activeNavigationField) as $card) {

            $cards->forget($cards->whereInstanceOf($card)->keys()->toArray());

        }

        $navigationCard = new ResourceNavigationCard($resources);
        $navigationCard->withMeta([
            'cardsToRemove' => $cardsToRemove,
        ]);

        /**
         * Inject the navigation card
         */
        $cards->prepend($navigationCard);

        return collect(array_values($this->filter($cards->toArray())))->each(static function (Card $card) {

            if (!$card instanceof ResourceNavigationCard) {

                $card->withMeta([ 'navigationTabClass' => get_class($card) ]);

            }

        });

    }

    /**
     * @param NovaRequest $request
     *
     * @return FieldCollection
     */
    public function availableFields(NovaRequest $request)
    {
        return new FieldCollection(array_values($this->filter($this->resolveActiveFields($request))));
    }

    /**
     * @param $label
     * @param NovaRequest $request
     *
     * @return mixed
     */
    protected function panelsWithDefaultLabel($label, NovaRequest $request)
    {
        return with(
            collect(array_values($this->flattenPanels($this->resolveActiveFields($request))))->whereInstanceOf(Panel::class)->values(),
            static function ($panels) use ($label) {
                return $panels->when($panels->where('name', $label)->isEmpty(), static function ($panels) use ($label) {
                    return $panels->prepend((new Panel($label))->withToolbar());
                })->all();
            }
        );
    }

    private function resolveNavigationResources(NovaRequest $request): Collection
    {
        return once(function () use ($request) {

            if ($request instanceof CardRequest || $request instanceof MetricRequest) {

                /**
                 * As this card is only meant to be used on the details page,
                 * if the fieldForDetail exist attempt to fetch the fields from it
                 */
                if (method_exists($this, 'fieldsForDetail')) {

                    $allFields = $this->fieldsForDetail($request);

                } else {

                    $allFields = $this->fields($request);

                }

            } else {

                $method = $this->fieldsMethod($request);
                $allFields = $this->{$method}($request);

            }

            return collect($allFields)
                ->whereInstanceOf(ResourceNavigationTab::class)
                ->filter(function (ResourceNavigationTab $field) use ($request) {
                    return $field->authorizedToSee($request);
                });

        });
    }

    /**
     * Move every sub panel outside of main resource tabs list
     *
     * @param array $fields
     *
     * @return array
     */
    private function flattenPanels(array $fields): array
    {

        foreach ($fields as $resourceNavigationTab) {

            if (!$resourceNavigationTab instanceof ResourceNavigationTab) {

                continue;

            }

            foreach ($resourceNavigationTab->data as $index => $field) {

                if ($field instanceof Panel) {

                    $fields[] = $field;
                    unset($resourceNavigationTab->data[ $index ]);

                }

            }

            $resourceNavigationTab->data = array_values($resourceNavigationTab->data);

        }

        return $fields;

    }

    /**
     * @param Request $request
     * @param ResourceNavigationTab $resourceNavigationTab
     *
     * @return array
     */
    private function getCardsToRemove(Request $request, ResourceNavigationTab $resourceNavigationTab): array
    {

        if ($resourceNavigationTab->cardMode->is(CardMode::EXCLUDE_ALL)) {

            return $this->cards($request);

        }

        if ($resourceNavigationTab->cardMode->is(CardMode::KEEP_ALL)) {

            return [];

        }

        $cards = $resourceNavigationTab->getCards();

        if ($resourceNavigationTab->cardMode->is(CardMode::EXCEPT)) {

            return $cards->toArray();

        }

        if ($resourceNavigationTab->cardMode->is(CardMode::ONLY)) {

            return collect($this->cards($request))->filter(static function (Card $card) use ($cards) {

                return !$cards->contains(get_class($card));

            })->toArray();

        }

        return [];

    }

    /**
     * @param NovaRequest $request
     *
     * @return string|null
     */
    private function getActiveTab(NovaRequest $request): ?string
    {
        return $_COOKIE[ 'navigation_tab' ] ?? null;
    }

    /**
     * @param NovaRequest $request
     *
     * @throws Throwable
     * @return ResourceNavigationTab
     */
    private function getActiveNavigationField(NovaRequest $request): ResourceNavigationTab
    {

        $activeTab = $this->getActiveTab($request);
        $navigationFields = $this->resolveNavigationResources($request);

        throw_if($navigationFields->isEmpty(), 'You should include at least 1 NavigationField in your fields array');

        if ($navigationField = $navigationFields->firstWhere('slug', $activeTab)) {

            return $navigationField;

        }

        return $navigationFields->first();

    }

    /**
     * @param NovaRequest $request
     *
     * @return array
     */
    private function resolveActiveFields(NovaRequest $request): array
    {

        $activeTab = $this->getActiveTab($request);

        $method = $this->fieldsMethod($request);
        $allFields = $this->{$method}($request);

        $fields = collect($allFields)->filter(static function ($field) use ($request) {

            if (method_exists($field, 'authorizedToSee')) {

                return $field->authorizedToSee($request);

            }

            return true;

        });

        if (!($request->route()->controller instanceof ResourceShowController)) {

            $fields->each(static function ($field) {

                if ($field instanceof ResourceNavigationTab && $field->shouldBehaveAsPanel()) {

                    $field->behaveAsPanel();

                }

            });

            return $fields->toArray();

        }

        $instances = $fields->whereInstanceOf(ResourceNavigationTab::class);
        $firstTab = $instances->first();

        $match = $instances->first(static function (ResourceNavigationTab $resourceNavigationTab) use ($activeTab) {
            return $resourceNavigationTab->isActive($activeTab);
        });

        /**
         * Remove all the nonActive NavigationField from the fields list
         */
        foreach ($fields as $index => $field) {

            if ($field instanceof ResourceNavigationTab) {

                if (($match === null && $field === $firstTab) || $match === $field) {

                    $fields->put($index, $field->data);

                } else {

                    $fields->forget($index);

                }

            }

        }

        return $fields->flatten()->toArray();

    }

}
