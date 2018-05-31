<?php

namespace DeepCopy\Filter;

use ReflectionProperty;

/**
 * @final
 */
class SetNullFilter implements Filter
{
    /**
     * Sets the object property to null.
     *
     * {@inheritdoc}
     */
    public function apply($object, ReflectionProperty $reflectionProperty, $objectCopier)
    {
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, null);
    }
}
