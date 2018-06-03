<?php declare(strict_types=1);

namespace DeepCopy\Filter;

use ReflectionProperty;

final class ReplaceFilter implements Filter
{
    private $callback;

    /**
     * @param callable $callable Will be called to get the new value for each property to replace
     */
    public function __construct(callable $callable)
    {
        $this->callback = $callable;
    }

    /**
     * Replaces the object property by the result of the callback called with the object property.
     *
     * {@inheritdoc}
     */
    public function apply(object $object, ReflectionProperty $reflectionProperty, callable $objectCopier): void
    {
        $reflectionProperty->setAccessible(true);

        $value = ($this->callback)($reflectionProperty->getValue($object));

        $reflectionProperty->setValue($object, $value);
    }
}
