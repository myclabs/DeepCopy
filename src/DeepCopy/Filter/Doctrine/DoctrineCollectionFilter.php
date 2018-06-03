<?php declare(strict_types=1);

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;
use Doctrine\Common\Collections\Collection;
use ReflectionProperty;

final class DoctrineCollectionFilter implements Filter
{
    /**
     * Copies the object property doctrine collection.
     *
     * {@inheritdoc}
     */
    public function apply(object $object, ReflectionProperty $reflectionProperty, callable $objectCopier): void
    {
        $reflectionProperty->setAccessible(true);
        /** @var Collection $oldCollection */
        $oldCollection = $reflectionProperty->getValue($object);

        $newCollection = $oldCollection->map(
            function ($item) use ($objectCopier) {
                return $objectCopier($item);
            }
        );

        $reflectionProperty->setValue($object, $newCollection);
    }
}
