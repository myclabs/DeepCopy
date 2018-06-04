<?php declare(strict_types=1);

namespace DeepCopy\Matcher;

use ReflectionProperty;

final class PropertyMatcher implements Matcher
{
    private $class;
    private $property;

    public function __construct(string $class, string $property)
    {
        $this->class = $class;
        $this->property = $property;
    }

    /**
     * Matches a specific property of a specific class.
     *
     * {@inheritdoc}
     */
    public function matches(object $object, ReflectionProperty $reflectionProperty): bool
    {
        return ($object instanceof $this->class) && $reflectionProperty->getName() === $this->property;
    }
}
