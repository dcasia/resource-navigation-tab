<?php

namespace DigitalCreative\ResourceNavigationTab;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\AuthorizedToSee;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
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
     * @var string
     */
    public $slug;

    /**
     * @var CardMode
     */
    public $cardMode;

    /**
     * @var array
     */
    private $default = [
        'label' => null,
        'resourceTableTitle' => null,
        'behaveAsPanel' => true,
        'resourceId' => null,
        'cardMode' => CardMode::KEEP_ALL,
        'cards' => [],
        'fields' => []
    ];

    /**
     * @var array
     */
    private $configuration;

    /**
     * ResourceNavigationTab constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {

        $this->configuration = array_merge($this->default, $configuration);

        $this->name = $this->getTableLabel(app(NovaRequest::class));
        $this->slug = $this->getResourceSlug();
        $this->data = $this->getFields();
        $this->cardMode = new CardMode($this->configuration[ 'cardMode' ]);

        $this->initializePermissions();

    }

    private function initializePermissions(): void
    {

        /**
         * Proxy canSee on all children
         *
         * @var Field $field
         */
        foreach ($this->data as $field) {

            /**
             * Ignore everything that doesnt have a seeCallback function
             */
            if (!method_exists($field, 'seeCallback')) {

                continue;

            }

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
     * @param string|null $activeTab
     *
     * @return bool
     */
    public function isActive(?string $activeTab): bool
    {
        return $this->getResourceSlug() === $activeTab;
    }

    /**
     * @param NovaRequest $request
     *
     * @return string
     */
    public function getTableLabel(NovaRequest $request): ?string
    {
        return $this->configuration[ 'label' ];
    }

    public function getTabLabel(NovaRequest $request): string
    {
        return $this->configuration[ 'resourceTableTitle' ] ?? $this->getTableLabel($request);
    }

    public function getResourceSlug(): string
    {
        return Str::slug($this->name);
    }

    public function getResourceId(): ?int
    {
        return $this->configuration[ 'resourceId' ] ?? null;
    }

    public function getCards(): Collection
    {
        return collect($this->configuration[ 'cards' ]);
    }

    private function getFields(): array
    {

        $fields = $this->configuration[ 'fields' ];

        if (is_callable($fields)) {

            return $fields(resolve(NovaRequest::class));

        }

        return $fields;

    }

    public function behaveAsPanel(): void
    {
        $this->data = $this->prepareFields($this->getFields());
    }

    public function shouldBehaveAsPanel(): bool
    {
        return $this->configuration[ 'behaveAsPanel' ];
    }

}
