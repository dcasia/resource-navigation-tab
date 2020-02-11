<?php

namespace DigitalCreative\ResourceNavigationTab;

class CardMode
{
    public const ONLY = 'only';
    public const EXCEPT = 'except';
    public const KEEP_ALL = 'keep-all';
    public const EXCLUDE_ALL = 'exclude-all';

    /**
     * @var string
     */
    private $value;

    /**
     * CardMode constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function is(string $value): bool
    {
        return $this->value === $value;
    }

}
