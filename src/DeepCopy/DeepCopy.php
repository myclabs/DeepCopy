<?php

namespace DeepCopy;

use DeepCopy\Filter\Filter;
use DeepCopy\Filter\SetNullFilter;
use ReflectionClass;

/**
 * DeepCopy
 */
class DeepCopy
{
    /**
     * @var array
     */
    private $hashMap = array();

    /**
     * Filters to apply.
     * @var FilterMatcher[]
     */
    private $filterMatchers = array();

    /**
     * Perform a deep copy of the object.
     * @param object $object
     * @return object
     */
    public function copy($object)
    {
        $this->hashMap = array();

        return $this->recursiveCopy($object);
    }

    public function addFilter($class, $property, Filter $filter)
    {
        $this->filterMatchers[] = new FilterMatcher($class, $property, $filter);
    }

    private function recursiveCopy($object)
    {
        $objectHash = spl_object_hash($object);

        if (isset($this->hashMap[$objectHash])) {
            return $this->hashMap[$objectHash];
        }

        $newObject = clone $object;

        $this->hashMap[$objectHash] = $newObject;

        // Clone properties
        $class = new ReflectionClass($object);
        foreach ($class->getProperties() as $property) {
            // Apply the filters
            foreach ($this->filterMatchers as $filterMatcher) {
                if ($filterMatcher->matches($newObject, $property->getName())) {
                    $filterMatcher->getFilter()->apply($newObject, $property->getName());
                    continue;
                }
            }

            $property->setAccessible(true);
            $propertyValue = $property->getValue($object);

            if (is_object($propertyValue)) {
                $property->setValue($object, $this->recursiveCopy($propertyValue));
            } elseif (is_array($propertyValue)) {
                $newPropertyValue = array();
                foreach ($propertyValue as $i => $item) {
                    $newPropertyValue[$i] = $this->recursiveCopy($item);
                }
                $property->setValue($object, $newPropertyValue);
            }
        }

        return $newObject;
    }
}
