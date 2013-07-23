<?php

namespace DeepCopy;

use DeepCopy\Filter\Filter;
use DeepCopy\Filter\SetNullFilter;
use DeepCopy\Matcher\Matcher;
use DeepCopy\Matcher\PropertyMatcher;
use ReflectionClass;

/**
 * DeepCopy
 */
class DeepCopy
{
    /**
     * @var array
     */
    private $hashMap = [];

    /**
     * Filters to apply.
     * @var array
     */
    private $filters = [];

    /**
     * Perform a deep copy of the object.
     * @param object $object
     * @return object
     */
    public function copy($object)
    {
        $this->hashMap = [];

        return $this->recursiveCopy($object);
    }

    public function addFilter(Filter $filter, Matcher $matcher)
    {
        $this->filters[] = [
            'matcher' => $matcher,
            'filter'  => $filter,
        ];
    }

    private function recursiveCopy($object)
    {
        // Resource
        if (is_resource($object)) {
            return $object;
        }
        // Array
        if (is_array($object)) {
            $newArray = [];
            foreach ($object as $i => $item) {
                $newArray[$i] = $this->recursiveCopy($item);
            }
            return $newArray;
        }
        // Scalar
        if (!is_object($object)) {
            return $object;
        }

        $objectHash = spl_object_hash($object);

        if (isset($this->hashMap[$objectHash])) {
            return $this->hashMap[$objectHash];
        }

        $newObject = clone $object;

        $this->hashMap[$objectHash] = $newObject;

        // Clone properties
        $class = new ReflectionClass($object);
        foreach ($class->getProperties() as $property) {
            // Ignore static properties
            if ($property->isStatic()) {
                continue;
            }

            // Apply the filters
            foreach ($this->filters as $item) {
                /** @var Matcher $matcher */
                $matcher = $item['matcher'];
                /** @var Filter $filter */
                $filter = $item['filter'];

                if ($matcher->matches($newObject, $property->getName())) {
                    $filter->apply(
                        $newObject,
                        $property->getName(),
                        function ($object) {
                            $this->recursiveCopy($object);
                        }
                    );
                    // If a filter matches, we stop processing this property
                    continue 2;
                }
            }

            $property->setAccessible(true);
            $propertyValue = $property->getValue($object);

            // Copy the property
            $property->setValue($object, $this->recursiveCopy($propertyValue));
        }

        return $newObject;
    }
}
