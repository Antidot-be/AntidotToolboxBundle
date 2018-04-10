<?php

namespace Antidot\ToolboxBundle\Services\BaseClass;

use Antidot\ToolboxBundle\Exceptions\LogicException;
use Antidot\ToolboxBundle\Interfaces\IdentifiableInterface;
use AppBundle\Entity\User;

class ClassTransformer
{
    /**
     * @var ClassMapper
     */
    private $classMapper;
    /**
     * @var ClassNameTransformer
     */
    private $classNameTransformer;

    public function __construct(ClassMapper $classMapper, ClassNameTransformer $classNameTransformer)
    {
        $this->classMapper          = $classMapper;
        $this->classNameTransformer = $classNameTransformer;
    }

    /**
     * @param string|\object $className
     *
     * @return string
     * @throws LogicException
     */
    public function getIcon($className): string
    {
        $className = $this->classNameTransformer->convertClassName($className);

        return (string)$this->classMapper->getIcon($className);
    }

    /**
     * @param string|\object $className
     *
     * @return string
     * @throws LogicException
     */
    public function getShowRoute($className): string
    {
        $className = $this->classNameTransformer->convertClassName($className);

        return (string)$this->classMapper->getShowRoute($className);
    }

    public function getAsString($object): string
    {
        $className = $this->classNameTransformer->getFullClassName($object);

        $rendererClass = $this->classMapper->getToStringRenderer($className);

        if (!class_exists($rendererClass)) {
            trigger_error(sprintf('Class "%s" does not exist', $rendererClass));
        }

        switch ($className) {
            case User::class:
                /** @var User $object */
                return sprintf('%s (%s)', $object->getUsername(), $object->getId());
            default:
                if ($object instanceof IdentifiableInterface) {
                    return $object->getId();
                }

                return (string)$object;
        }
    }
}
