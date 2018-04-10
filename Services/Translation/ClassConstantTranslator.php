<?php

namespace Antidot\ToolboxBundle\Services\Translation;

use Antidot\ToolboxBundle\Exceptions\Development\GetterException;

class ClassConstantTranslator extends Translator
{
    protected $field;

    public function getDomain(): string
    {
        return 'class-constant';
    }

    /**
     * @return string
     * @throws GetterException
     */
    public function getField(): string
    {
        if ($this->field === null) {
            throw new GetterException('field');
        }

        return $this->field;
    }

    /**
     * @param string $class
     * @param string $item
     *
     * @return string
     * @throws GetterException
     */
    public function getKey(string $class, string $item): string
    {
        return sprintf('%s.%s.%s', $class, $this->getField(), $item);
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }
}
