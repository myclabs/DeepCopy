<?php

namespace DeepCopy\Filter;

use ReflectionProperty;

/**
 * Sets a null value for a property.
 *
 * @final
 */
class SetNullFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function apply($object, $property, $objectCopier)
    {
        $reflectionProperty = new ReflectionProperty($object, $property);

        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, null);
    }
}
