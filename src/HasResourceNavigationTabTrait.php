<?php

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\FieldCollection;
use Laravel\Nova\Http\Controllers\ResourceShowController;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Throwable;

trait HasResourceNavigationTabTrait
{

    /**
     * Resolve the detail fields and assign them to their associated panel.
     *
     * @param NovaRequest $request
     *
     * @return FieldCollection
     * @throws Throwable
     */
    public function detailFieldsWithinPanels(NovaRequest $request)
    {
        $activeNavigationField = $this->getActiveNavigationField($request);

        return $this->assignToPanels(
            $activeNavigationField->getTableLabel(), $this->detailFields($request)
        );
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
            collect(array_values($this->resolveActiveFields($request)))->whereInstanceOf(Panel::class)->values(),
            static function ($panels) use ($label) {
                return $panels->when($panels->where('name', $label)->isEmpty(), static function ($panels) use ($label) {
                    return $panels->prepend((new Panel($label))->withToolbar());
                })->all();
            }
        );
    }

    public function availablePanelsForDetail($request)
    {

        $label = Panel::defaultNameForDetail($request->newResource());
        $panels = $this->panelsWithDefaultLabel($label, $request);

        $navigationField = $this->getActiveNavigationField($request);

        /**
         * Replace the name of the active panel with the same label as the tab
         */
        collect($panels)->firstWhere('name', $label)->name = $navigationField->getTableLabel();

        return $panels;

    }

    /**
     * Get the cards for the given request.
     *
     * @param NovaRequest $request
     *
     * @return Collection
     * @throws Throwable
     */
    public function resolveCards(NovaRequest $request)
    {

        $activeNavigationField = $this->getActiveNavigationField($request);
        $cards = collect($this->cards($request));
        $cardsToRemove = collect();

        $resources = collect($this->fields($request))
            ->whereInstanceOf(ResourceNavigationTab::class)
            ->filter(static function (ResourceNavigationTab $field) use ($request) {
                return $field->authorizedToSee($request);
            })
            ->each(static function (ResourceNavigationTab $field) use ($cardsToRemove, $cards) {

                /**
                 * Avoids the cards flashing when switching tabs
                 */
                if ($field->withCards) {

                    $cardsToRemove->put(
                        $field->id,
                        collect($field->cardsToRemove)->map(static function ($card) use ($cards) {
                            return $cards->whereInstanceOf($card)->map(static function ($card) {
                                return get_class($card);
                            });
                        })->flatten()
                    );

                } else {

                    $cardsToRemove->put($field->id, $cards->map(static function ($card) {
                        return get_class($card);
                    }));

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
            'cardsToRemove' => $cardsToRemove
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
     * @param Request $request
     * @param ResourceNavigationTab $resourceNavigationTab
     *
     * @return array
     */
    private function getCardsToRemove(Request $request, ResourceNavigationTab $resourceNavigationTab): array
    {

        if (!blank($resourceNavigationTab->cardsToRemove)) {

            return $resourceNavigationTab->cardsToRemove;

        }

        if (!$resourceNavigationTab->withCards) {

            return $this->cards($request);

        }

        return [];

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
     * @param NovaRequest $request
     *
     * @return string|null
     */
    private function getActiveTab(NovaRequest $request): ?string
    {
        return $request->query('navigationTab');
    }

    /**
     * @param NovaRequest $request
     *
     * @return ResourceNavigationTab
     * @throws Throwable
     */
    private function getActiveNavigationField(NovaRequest $request): ResourceNavigationTab
    {

        $fields = collect($this->fields($request));
        $activeTab = $this->getActiveTab($request);
        $navigationFields = $fields->whereInstanceOf(ResourceNavigationTab::class)
                                   ->filter(static function (ResourceNavigationTab $field) use ($request) {
                                       return $field->authorizedToSee($request);
                                   });

        throw_if($navigationFields->isEmpty(), 'You should include at least 1 NavigationField in your fields array');

        if ($navigationField = $navigationFields->firstWhere('id', $activeTab)) {

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
        $fields = collect($this->fields($request))->filter(static function ($field) use ($request) {

            if (method_exists($field, 'authorizedToSee')) {

                return $field->authorizedToSee($request);

            }

            return true;

        });

        if (!($request->route()->controller instanceof ResourceShowController)) {

            $fields->each(static function ($field) {

                if ($field instanceof ResourceNavigationTab) {

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
