<?php

namespace DeepCopy\Matcher;

use ReflectionProperty;

/**
 * Match a specific property of a specific class
 */
class PropertyMatcher implements Matcher
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
     * @param string $class    Class name
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
    public function matches($object, ReflectionProperty $reflectionProperty)
    {
        return ($object instanceof $this->class) && ($reflectionProperty->getName() == $this->property);
    }
}
