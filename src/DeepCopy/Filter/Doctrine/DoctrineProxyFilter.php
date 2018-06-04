<?php declare(strict_types=1);

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\ChainableFilter;
use Doctrine\Common\Persistence\Proxy;
use ReflectionProperty;

final class DoctrineProxyFilter implements ChainableFilter
{
    /**
     * Triggers the magic method __load() on a Doctrine Proxy class to load the
     * actual entity from the database.
     *
     * {@inheritdoc}
     *
     * @param Proxy $object
     */
    public function apply(object $object, ReflectionProperty $reflectionProperty, callable $objectCopier): void
    {
        $object->__load();
    }
}
