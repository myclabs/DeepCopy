<?php

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;
use ReflectionProperty;

/**
 * Set a null value for a property
 */
class CollectionFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function apply($object, $property)
    {
        $reflectionProperty = new ReflectionProperty($object, $property);

        $reflectionProperty->setAccessible(true);
        $oldCollection = $reflectionProperty->getValue($object);

        $newCollection = $oldCollection->map(
            function ($item) {
                // TODO copy with DeepCopy
                return clone $item;
            }
        );

        $reflectionProperty->setValue($newCollection);
    }
}
