<?php

namespace Antidot\ToolboxBundle\Services\BaseClass;

class ClassMapper
{
    /**
     * @var array
     */
    private $iconsMap;
    /**
     * @var array
     */
    private $showRoutesMap;
    /**
     * @var array
     */
    private $toStringRendersMap;

    public function __construct(array $iconsMap, array $showRoutesMap, array $toStringRendersMap)
    {

        $this->iconsMap      = $iconsMap;
        $this->showRoutesMap = $showRoutesMap;
        $this->toStringRendersMap = $toStringRendersMap;
    }

    public function getIcon(string $key): ?string
    {
        if (array_key_exists($key, $this->iconsMap)) {
            return $this->iconsMap[$key];
        }

        return null;
    }

    public function getShowRoute(string $key): ?string
    {
        if (array_key_exists($key, $this->showRoutesMap)) {
            return $this->showRoutesMap[$key];
        }

        return null;
    }

    public function getToStringRenderer(string $key): ?string
    {
        if (array_key_exists($key, $this->toStringRendersMap)) {
            return $this->toStringRendersMap[$key];
        }

        return null;
    }
}
