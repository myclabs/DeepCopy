<?php

namespace DeepCopy\Filter;

/**
 * Keep the value of a property
 */
class KeepFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function apply($object, $property, $objectCopier)
    {
        // Nothing to do
    }
}
