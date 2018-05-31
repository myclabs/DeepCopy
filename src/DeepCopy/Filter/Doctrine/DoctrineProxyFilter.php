<?php

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;
use ReflectionProperty;

/**
 * @final
 */
class DoctrineProxyFilter implements Filter
{
    /**
     * Triggers the magic method __load() on a Doctrine Proxy class to load the
     * actual entity from the database.
     *
     * {@inheritdoc}
     */
    public function apply($object, ReflectionProperty $reflectionProperty, $objectCopier)
    {
        $object->__load();
    }
}
