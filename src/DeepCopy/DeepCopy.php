<?php

namespace DeepCopy;

use ReflectionClass;

/**
 * DeepCopy
 */
class DeepCopy
{
    private $hashMap = array();

    public function copy($object)
    {
        $this->hashMap = array();

        return $this->recursiveCopy($object);
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
