<?php

namespace DeepCopy\Filter;

use ReflectionProperty;

/**
 * Set a null value for a property
 */
class SetNullFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function apply($object, ReflectionProperty $reflectionProperty, $objectCopier)
    {
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, null);
    }
}
