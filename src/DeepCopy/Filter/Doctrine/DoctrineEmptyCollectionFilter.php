<?php

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;
use Doctrine\Common\Collections\ArrayCollection;

class DoctrineEmptyCollectionFilter implements Filter
{
    /**
     * Apply the filter to the object.
     *
     * @param object   $object
     * @param string   $property
     * @param callable $objectCopier
     */
    public function apply($object, $property, $objectCopier)
    {
        $reflectionProperty = new \ReflectionProperty($object, $property);
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($object, new ArrayCollection());
    }
} 