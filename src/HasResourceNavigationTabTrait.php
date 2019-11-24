<?php

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Support\Collection;
use Laravel\Nova\Fields\FieldCollection;
use Laravel\Nova\Http\Controllers\ResourceShowController;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

trait HasResourceNavigationTabTrait
{

    /**
     * Resolve the detail fields and assign them to their associated panel.
     *
     * @param NovaRequest $request
     * @return FieldCollection
     */
    public function detailFieldsWithinPanels(NovaRequest $request)
    {
        $activeNavigationField = $this->getActiveNavigationField($request);

        return $this->assignToPanels(
            $activeNavigationField->getTableLabel(), $this->detailFields($request)
        );
    }

    protected function panelsWithDefaultLabel($label, NovaRequest $request)
    {
        return with(
            collect(array_values($this->resolveActiveFields($request)))->whereInstanceOf(Panel::class)->values(),
            function ($panels) use ($label) {
                return $panels->when($panels->where('name', $label)->isEmpty(), function ($panels) use ($label) {
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
     * @return Collection
     */
    public function resolveCards(NovaRequest $request)
    {

        $activeNavigationField = $this->getActiveNavigationField($request);
        $cards = collect($this->cards($request));
        $cardsToRemove = collect();

        $resources = collect($this->fields($request))
            ->whereInstanceOf(ResourceNavigationTab::class)
            ->each(function (ResourceNavigationTab $field) use ($cardsToRemove, $cards) {

                /**
                 * Avoids the cards flashing when switching tabs
                 */
                if ($field->withCards) {

                    $cardsToRemove->put(
                        $field->id,
                        collect($field->cardsToRemove)->map(function ($card) use ($cards) {
                            return $cards->whereInstanceOf($card)->map->component();
                        })->flatten()
                    );

                } else {

                    $cardsToRemove->put($field->id, $cards->map->component());

                }

            })
            ->mapInto(Resource::class)
            ->values();

        if ($activeNavigationField) {

            foreach ($activeNavigationField->cardsToRemove as $card) {

                $cards->forget($cards->whereInstanceOf($card)->keys()->toArray());

            }

        }

        $navigationCard = new ResourceNavigationCard($resources);
        $navigationCard->withMeta([
            'cardsToRemove' => $cardsToRemove
        ]);

        /**
         * Inject the navigation card
         */
        $cards->prepend($navigationCard);

        return collect(array_values($this->filter($cards->toArray())));

    }

    public function availableFields(NovaRequest $request)
    {
        return new FieldCollection(array_values($this->filter($this->resolveActiveFields($request))));
    }

    private function getActiveTab(NovaRequest $request): ?string
    {
        parse_str(parse_url($request->server('HTTP_REFERER'), PHP_URL_QUERY), $query);

        return $query[ 'tab' ] ?? null;
    }

    private function getActiveNavigationField(NovaRequest $request): ResourceNavigationTab
    {

        $fields = collect($this->fields($request));
        $activeTab = $this->getActiveTab($request);
        $navigationFields = $fields->whereInstanceOf(ResourceNavigationTab::class);

        throw_if($navigationFields->isEmpty(), 'You should include at least 1 NavigationField in your fields array');

        if ($navigationField = $navigationFields->firstWhere('id', $activeTab)) {

            return $navigationField;

        }

        return $navigationFields->first();

    }

    private function resolveActiveFields(NovaRequest $request): array
    {

        $activeTab = $this->getActiveTab($request);
        $fields = collect($this->fields($request));
        $firstTab = $fields->whereInstanceOf(ResourceNavigationTab::class)->first();

        $controller = $request->route()->controller;

        if (!($controller instanceof ResourceShowController)) {

            $fields->each(function ($field) {

                if ($field instanceof ResourceNavigationTab) {

                    $field->behaveAsPanel();

                }

            });

            return $fields->toArray();

        }

        /**
         * Remove all the nonActive NavigationField from the fields list
         */
        foreach ($fields as $index => $field) {

            if ($field instanceof ResourceNavigationTab) {

                if (is_null($activeTab) && $field === $firstTab || $field->isActive($activeTab)) {

                    $fields->put($index, $field->data);

                } else {

                    $fields->forget($index);

                }

            }

        }

        return $fields->flatten()->toArray();

    }

}
