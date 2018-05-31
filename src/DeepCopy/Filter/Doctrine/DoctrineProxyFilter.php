<?php

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\ChainableFilter;
use ReflectionProperty;

/**
 * @final
 */
class DoctrineProxyFilter implements ChainableFilter
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
