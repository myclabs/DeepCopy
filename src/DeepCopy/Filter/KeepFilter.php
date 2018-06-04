<?php declare(strict_types=1);

namespace DeepCopy\Filter;

use ReflectionProperty;

final class KeepFilter implements Filter
{
    /**
     * Keeps the value of the object property.
     *
     * {@inheritdoc}
     */
    public function apply(object $object, ReflectionProperty $reflectionProperty, callable $objectCopier): void
    {
        // Nothing to do
    }
}
