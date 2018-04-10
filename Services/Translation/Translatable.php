<?php

namespace Antidot\ToolboxBundle\Services\Translation;

interface Translatable
{
    public function getKey(string $class, string $item): string;

    public function getDomain(): string;
}
