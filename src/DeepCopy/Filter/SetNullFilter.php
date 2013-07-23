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
    public function apply($object, $property)
    {
        $reflectionProperty = new ReflectionProperty($object, $property);

        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, null);
    }
}
