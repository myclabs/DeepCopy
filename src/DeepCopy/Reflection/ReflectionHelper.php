<?php declare(strict_types=1);

namespace DeepCopy\Reflection;

use ReflectionClass;
use ReflectionProperty;

final class ReflectionHelper
{
    /**
     * Retrieves all properties (including private ones), from object and all its ancestors.
     *
     * Standard \ReflectionClass->getProperties() does not return private properties from ancestor classes.
     *
     * @author muratyaman@gmail.com
     * @see https://secure.php.net/manual/en/reflectionclass.getproperties.php
     *
     * @param ReflectionClass $reflectionClass
     *
     * @return ReflectionProperty[]
     */
    public static function getProperties(ReflectionClass $reflectionClass): array
    {
        $reflectionProperties = $reflectionClass->getProperties();
        $reflectionPropertiesByName = [];

        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $reflectionPropertiesByName[$propertyName] = $reflectionProperty;
        }

        if ($parentClass = $reflectionClass->getParentClass()) {
            $parentReflectionPropertiesByName = self::getProperties($parentClass);

            foreach ($reflectionPropertiesByName as $name => $reflectionProperty) {
                // When a property collides by name, the child one takes precedence
                $parentReflectionPropertiesByName[$name] = $reflectionProperty;
            }

            return $parentReflectionPropertiesByName;
        }

        return $reflectionPropertiesByName;
    }
}
