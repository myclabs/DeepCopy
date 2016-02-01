<?php

namespace DeepCopy\Filter;

use ReflectionProperty;

/**
 * Filter to apply to a property while copying an object
 */
interface Filter
{
    /**
     * Apply the filter to the object.
     * @param object                $object
     * @param ReflectionProperty    $reflectionProperty
     * @param callable              $objectCopier
     */
    public function apply($object, ReflectionProperty $reflectionProperty, $objectCopier);
}
