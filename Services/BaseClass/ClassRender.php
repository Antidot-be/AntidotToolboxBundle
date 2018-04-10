<?php

namespace Antidot\ToolboxBundle\Services\BaseClass;

use Antidot\ToolboxBundle\Exceptions\LogicException;
use Antidot\ToolboxBundle\Interfaces\IdentifiableInterface;
use Symfony\Component\Routing\RouterInterface;

class ClassRender
{
    /**
     * @var ClassNameTransformer
     */
    private $classNameTransformer;
    /**
     * @var ClassTransformer
     */
    private $classTransformer;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        ClassTransformer $classTransformer,
        ClassNameTransformer $classNameTransformer,
        RouterInterface $router
    ) {
        $this->classNameTransformer = $classNameTransformer;
        $this->classTransformer     = $classTransformer;
        $this->router               = $router;
    }

    /**
     * @param string|\object $className
     *
     * @return string
     */
    public function getHtmlIcon($className): string
    {
        try {
            if ($iconCssClass = $this->classTransformer->getIcon($className)) {
                return sprintf('<span class="fa fa-fw %s"></span>', $iconCssClass);
            }
        } catch (LogicException $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }

        return '';
    }

    /**
     * @param \object $object
     *
     * @return string
     */
    public function getShowUrl(\object $object): string
    {
        $className = $this->classNameTransformer->getFullClassName($object);

        try {
            if (
                $object instanceof IdentifiableInterface
                && $route = $this->classTransformer->getShowRoute($className)
            ) {
                return $this->router->generate($route, ['id' => $object->getId()]);
            }
        } catch (LogicException $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }

        return '';
    }
}
