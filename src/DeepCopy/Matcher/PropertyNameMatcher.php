<?php declare(strict_types=1);

namespace DeepCopy\Matcher;

use ReflectionProperty;

final class PropertyNameMatcher implements Matcher
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
    public function matches(object $object, ReflectionProperty $reflectionProperty): bool
    {
        return $reflectionProperty->getName() === $this->property;
    }
}
