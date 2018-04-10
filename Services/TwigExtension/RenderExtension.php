<?php

namespace Antidot\ToolboxBundle\Services\TwigExtension;

use Antidot\ToolboxBundle\Interfaces\IdentifiableInterface;
use Antidot\ToolboxBundle\Services\BaseClass\ClassNameTransformer;
use Antidot\ToolboxBundle\Services\BaseClass\ClassRender;
use Antidot\ToolboxBundle\Services\BaseClass\ClassTransformer;
use Antidot\ToolboxBundle\Services\Translation\ClassConstantTranslator;
use Antidot\ToolboxBundle\Services\Translation\PropertyTranslator;
use Antidot\ToolboxBundle\Services\Translation\StatusTranslator;
use Interfaces\CanonicalKeyInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\TwigFilter;

class RenderExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var StatusTranslator
     */
    private $statusTranslator;
    /**
     * @var PropertyTranslator
     */
    private $propertyTranslator;
    /**
     * @var ClassConstantTranslator
     */
    private $classConstantTranslator;
    /**
     * @var ClassNameTransformer
     */
    private $classNameTransformer;
    /**
     * @var ClassTransformer
     */
    private $classTransformer;
    /**
     * @var ClassRender
     */
    private $classRender;

    public function __construct(
        TranslatorInterface $translator,
        StatusTranslator $statusTranslator,
        PropertyTranslator $propertyTranslator,
        ClassConstantTranslator $classConstantTranslator,
        ClassNameTransformer $classNameTransformer,
        ClassTransformer $classTransformer,
        ClassRender $classRender
    ) {
        $this->translator              = $translator;
        $this->statusTranslator        = $statusTranslator;
        $this->propertyTranslator      = $propertyTranslator;
        $this->classConstantTranslator = $classConstantTranslator;
        $this->classNameTransformer    = $classNameTransformer;
        $this->classTransformer        = $classTransformer;
        $this->classRender             = $classRender;
    }

    public function getDate(\DateTime $dateTime): string
    {
        return $dateTime->format('d/m/Y');
    }

    public function getDateTime(\DateTime $dateTime): string
    {
        return $dateTime->format('d/m/Y H:i:s');
    }

    public function getAmount(?float $amount, $precision = 2, bool $withPrefix = true): string
    {
        $prefix = $withPrefix ? '€' . utf8_encode(chr(0x00A0)) : '';

        return $prefix . $this->getFloat($amount, $precision);
    }

    public function getFloat(?float $amount, $precision = 2): string
    {
        if ($amount === null) {
            $amount = 0;
        }

        if ($precision === null) {
            $decimals = explode('.', (string)$amount);
            if (!isset($decimals[1])) {
                $precision = 0;
            } else {
                $precision = strlen($decimals[1]);
            }
        }

        return number_format($amount, $precision, ',', utf8_encode(chr(0x00A0)));
    }

    public function getPercentage(float $percentage): string
    {
        return number_format($percentage * 100, 2, ',', '.') . utf8_encode(chr(0x00A0)) . '%';
    }

    public function getStatus(int $status, string $class, string $locale = null): string
    {
        return $this->statusTranslator->trans($class, (string)$status, $locale);
    }

    /**
     * Translate a property of a class
     * Don't use it to translate something else
     *
     * @param string      $property
     * @param string      $class
     * @param string|null $locale
     *
     * @return string
     */
    public function getProperty(string $property, string $class = '_common', string $locale = null): string
    {
        return $this->propertyTranslator->trans($class, $property, $locale);
    }

    /**
     * Translate a CanonicalKey object
     *
     * @param CanonicalKeyInterface|null $config
     * @param string                     $key
     * @param string|null                $locale
     *
     * @return string
     */
    public function getConfig(?CanonicalKeyInterface $config, string $key = 'key', string $locale = null): string
    {
        if ($config === null) {
            return '-';
        }

        return $this->translator->trans(
            sprintf('%s.%s', $config->getCanonicalKey(), $key),
            [],
            'config-' . $this->classNameTransformer->getShortClassName($config),
            $locale
        );
    }

    /**
     * Translate a constant, independent of classes or objects.
     * For example, "Yes" and "No", colors, ...
     *
     * @param int         $constant
     * @param string      $class
     * @param string      $field
     * @param string|null $locale
     *
     * @return string
     */
    public function getClassConstant(int $constant, string $class, string $field, string $locale = null)
    {
        return $this->classConstantTranslator->setField($field)->trans($class, (string)$constant, $locale);
    }

    public function getBoolean(?bool $value): string
    {
        $key = $value ? 'boolean.yes' : 'boolean.no';

        return $this->translator->trans($key, [], 'constant');
    }

    public function getIconCssClass(string $className): string
    {
        return $this->classRender->getHtmlIcon($className);
    }

    public function getShowUrl(IdentifiableInterface $object): string
    {
        return $this->classTransformer->getShowUrl($object);
    }

    public function getLink($object, bool $withIcon = true)
    {
        return sprintf(
            '<a href="%s">%s%s</a>',
            $this->classTransformer->getShowUrl($object),
            $withIcon ? $this->getIconCssClass($this->classNameTransformer->getFullClassName($object)) : '',
            $this->classTransformer->getAsString($object)
        );
    }

    public function getFilters()
    {
        return [
            new TwigFilter('renderDate', [$this, 'getDate']),
            new TwigFilter('renderDatetime', [$this, 'getDateTime']),
            new TwigFilter('renderAmount', [$this, 'getAmount'], ['is_safe' => ['html']]),
            new TwigFilter('renderPercentage', [$this, 'getPercentage'], ['is_safe' => ['html']]),
            new TwigFilter('renderStatus', [$this, 'getStatus']),
            new TwigFilter('renderProperty', [$this, 'getProperty']),
            new TwigFilter('renderConfig', [$this, 'getConfig']),
            new TwigFilter('renderClassConstant', [$this, 'getClassConstant']),
            new TwigFilter('renderBoolean', [$this, 'getBoolean']),
            new TwigFilter('renderIconCssClass', [$this, 'getIconCssClass'], ['is_safe' => ['html']]),
            new TwigFilter('renderShowUrl', [$this, 'getShowUrl'], ['is_safe' => ['html']]),
            new TwigFilter('renderLink', [$this, 'getLink'], ['is_safe' => ['html']]),
        ];
    }

    public function getName()
    {
        return 'render';
    }
}
