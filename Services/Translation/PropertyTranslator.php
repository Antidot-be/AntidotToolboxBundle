<?php

namespace Antidot\ToolboxBundle\Services\Translation;

class PropertyTranslator extends Translator
{
    public function getDomain(): string
    {
        return 'class-property';
    }
}
