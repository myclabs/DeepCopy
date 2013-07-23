<?php

namespace DeepCopy\Filter;

/**
 * Filter to apply to a property while copying an object
 */
interface Filter
{
    /**
     * Returns true if the filter applies to the object.
     * @param object $object
     * @return boolean
     */
    public function applies($object);

    /**
     * Apply the filter to the object.
     * @param object $object
     */
    public function apply($object);
}
