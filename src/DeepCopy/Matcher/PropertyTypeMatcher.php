<?php declare(strict_types=1);

namespace DeepCopy\Matcher;

use ReflectionProperty;

/**
 * Matches a property by its type.
 *
 * It is recommended to use {@see DeepCopy\TypeFilter\TypeFilter} instead, as it applies on all occurrences
 * of given type in copied context (eg. array elements), not just on object properties.
 */
final class PropertyTypeMatcher implements Matcher
{
    private $propertyType;

    public function __construct(string $propertyType)
    {
        $this->propertyType = $propertyType;
    }

    /**
     * {@inheritdoc}
     */
    public function matches(object $object, ReflectionProperty $reflectionProperty): bool
    {
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object) instanceof $this->propertyType;
    }
}
