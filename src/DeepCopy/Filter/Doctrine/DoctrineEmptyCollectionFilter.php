<?php

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionProperty;

class DoctrineEmptyCollectionFilter implements Filter
{
    /**
     * Apply the filter to the object.
     *
     * @param object   $object
     * @param string   $property
     * @param callable $objectCopier
     */
    public function apply($object, ReflectionProperty $reflectionProperty, $objectCopier)
    {
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($object, new ArrayCollection());
    }
} 