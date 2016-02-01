<?php

namespace DeepCopy\Matcher;

use ReflectionProperty;

/**
 * Match a property by its name
 */
class PropertyNameMatcher implements Matcher
{
    /**
     * @var string
     */
    private $property;

    /**
     * @param string $property Property name
     */
    public function __construct($property)
    {
        $this->property = $property;
    }

    /**
     * {@inheritdoc}
     */
    public function matches($object, ReflectionProperty $reflectionProperty)
    {
        return $reflectionProperty->getName() == $this->property;
    }
}
