<?php

namespace DeepCopy\Filter\Doctrine;

use DeepCopy\Filter\Filter;
use DeepCopy\Reflection\ReflectionHelper;

/**
 * @final
 */
class DoctrineCollectionFilter implements Filter
{
    /** @var array<class-string> */
    private $ignoreClasses = [];

    /**
     * @param array<class-string> $ignoreClasses List of classes that should not be copied over to the new collection
     */
    public function __construct($ignoreClasses = [])
    {
        $this->ignoreClasses = $ignoreClasses;
    }

    /**
     * Copies the object property doctrine collection.
     *
     * {@inheritdoc}
     */
    public function apply($object, $property, $objectCopier)
    {
        $reflectionProperty = ReflectionHelper::getProperty($object, $property);

        $reflectionProperty->setAccessible(true);
        $collection = $reflectionProperty->getValue($object);

        if (!empty($this->ignoreClasses)) {
            $collection = $collection->filter(
                function ($item) {
                    foreach ($this->ignoreClasses as $ignoredClass) {
                        if (is_a($item, $ignoredClass, true)) {
                            return false;
                        }
                    }

                    return true;
                }
            );
        }

        $collection = $collection->map(
            function ($item) use ($objectCopier) {
                return $objectCopier($item);
            }
        );

        $reflectionProperty->setValue($object, $collection);
    }
}
