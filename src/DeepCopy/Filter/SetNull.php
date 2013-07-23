<?php

namespace DeepCopy\Filter;

use ReflectionProperty;

/**
 * Set a null value for a property
 */
class SetNull implements Filter
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $property;

    /**
     * @param string $class Class name
     * @param string $property Property name
     */
    public function __construct($class, $property)
    {
        $this->class = $class;
        $this->property = $property;
    }

    /**
     * {@inheritdoc}
     */
    public function applies($object)
    {
        return $object instanceof $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($object)
    {
        $reflectionProperty = new ReflectionProperty($object, $this->property);

        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, null);
    }
}
