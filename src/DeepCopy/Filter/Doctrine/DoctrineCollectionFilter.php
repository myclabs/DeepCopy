<?php

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;
use ReflectionProperty;

/**
 * Set a null value for a property
 */
class DoctrineCollectionFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function apply($object, ReflectionProperty $reflectionProperty, $objectCopier)
    {
        $reflectionProperty->setAccessible(true);
        $oldCollection = $reflectionProperty->getValue($object);

        $newCollection = $oldCollection->map(
            function ($item) use ($objectCopier) {
                return $objectCopier($item);
            }
        );

        $reflectionProperty->setValue($object, $newCollection);
    }
}
