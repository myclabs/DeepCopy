<?php

namespace DeepCopy\TypeFilter;

interface TypeFilter
{
    /**
     * Apply the filter to the object.
     * @param mixed $element
     */
    public function apply($element);
}
