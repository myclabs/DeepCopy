<?php declare(strict_types=1);

namespace DeepCopy\Filter;

use ReflectionProperty;

final class SetNullFilter implements Filter
{
    /**
     * Sets the object property to null.
     *
     * {@inheritdoc}
     */
    public function apply(object $object, ReflectionProperty $reflectionProperty, callable $objectCopier): void
    {
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, null);
    }
}
