<?php

namespace DeepCopy\Filter;

class KeepFilter implements Filter
{
    /**
     * Keeps the value of the object property.
     *
     * {@inheritdoc}
     */
    public function apply(object $object, string $property, ?callable $objectCopier)
    {
        // Nothing to do
    }
}
