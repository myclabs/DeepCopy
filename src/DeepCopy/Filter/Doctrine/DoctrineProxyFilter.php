<?php

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;

/**
 * Keep the value of a property
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
