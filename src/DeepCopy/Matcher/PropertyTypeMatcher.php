<?php

namespace DeepCopy\Matcher;

use DeepCopy\Reflection\ReflectionHelper;

/**
 * Match a property by its type
 *
 * It is recommended to use {@see DeepCopy\TypeFilter\TypeFilter} instead, as it applies on all occurrences
 * of given type in copied context (eg. array elements), not just on object properties.
 *
 * @final
 */
class PropertyTypeMatcher implements Matcher
{
    /**
     * @var string
     */
    private $propertyType;

    /**
     * @param string $propertyType Property type
     */
    public function __construct($propertyType)
    {
        $this->propertyType = $propertyType;
    }

    /**
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        $reflectionProperty = ReflectionHelper::getProperty($object, $property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object) instanceof $this->propertyType;
    }
}
