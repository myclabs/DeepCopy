<?php

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;

/**
 * Trigger the magic method __load() on a Doctrine Proxy class to load the
 * actual entity from the database.
 */
class DoctrineProxyFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function apply($object, $property, $objectCopier)
    {
        $object->__load();
    }
}
