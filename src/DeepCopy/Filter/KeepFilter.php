<?php

namespace DeepCopy\Filter;

use ReflectionProperty;

class KeepFilter implements Filter
{
    /**
     * Keeps the value of the object property.
     *
     * {@inheritdoc}
     */
    public function apply($object, ReflectionProperty $reflectionProperty, $objectCopier)
    {
        // Nothing to do
    }
}
