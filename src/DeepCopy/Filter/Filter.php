<?php

namespace DeepCopy\Filter;

/**
 * Filter to apply to a property while copying an object
 */
interface Filter
{
    /**
     * Applies the filter to the object.
     *
     * @param object $object
     * @param string $property
     * @param callable|null $objectCopier
     *
     * @return void
     */
    public function apply(object $object, string $property, ?callable $objectCopier);
}
