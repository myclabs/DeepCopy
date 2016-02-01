<?php

namespace DeepCopy\Filter;

use ReflectionProperty;

/**
 * Keep the value of a property
 */
class KeepFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function apply($object, ReflectionProperty $reflectionProperty, $objectCopier)
    {
        // Nothing to do
    }
}
