<?php

namespace DeepCopy\Matcher;

use ReflectionProperty;

/**
 * @final
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
     * Matches a property by its name.
     *
     * {@inheritdoc}
     */
    public function matches($object, ReflectionProperty $reflectionProperty)
    {
        return $reflectionProperty->getName() == $this->property;
    }
}
