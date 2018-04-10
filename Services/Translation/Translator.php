<?php

namespace Antidot\ToolboxBundle\Services\Translation;

use Symfony\Component\Translation\TranslatorInterface;

abstract class Translator implements Translatable
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function trans(string $class, string $item, string $locale = null): string
    {
        return $this->translator->trans($this->getKey($class, $item), [], $this->getDomain(), $locale);
    }

    public function getKey(string $class, string $item): string
    {
        return sprintf('%s.%s', $class, $item);
    }
}
