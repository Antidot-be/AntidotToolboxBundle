<?php

namespace Antidot\ToolboxBundle\Services\BaseClass;

use Antidot\ToolboxBundle\Exceptions\LogicException;

class ClassNameTransformer
{
    public function getFullClassName(\object $object): string
    {
        return str_replace('Proxies\\__CG__\\', '', get_class($object));
    }

    public function getShortClassName(\object $object): string
    {
        $explodedClass = explode('\\', get_class($object));

        return array_pop($explodedClass);
    }

    /**
     * @param string|\object $className
     *
     * @return string
     * @throws LogicException
     */
    public function convertClassName($className): string
    {
        if (is_object($className)) {
            return $this->getFullClassName($className);
        } elseif (is_string($className)) {
            return $className;
        }

        throw new LogicException(
            sprintf('Expected string or object, %s given', get_class($className))
        );
    }
}
