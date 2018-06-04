<?php declare(strict_types=1);

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionProperty;

final class DoctrineEmptyCollectionFilter implements Filter
{
    /**
     * Sets the object property to an empty doctrine collection.
     *
     * {@inheritdoc}
     */
    public function apply(object $object, ReflectionProperty $reflectionProperty, callable $objectCopier): void
    {
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, new ArrayCollection());
    }
} 