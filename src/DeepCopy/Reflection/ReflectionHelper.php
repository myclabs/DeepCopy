<?php

namespace DeepCopy\Reflection;

class ReflectionHelper
{
    /**
     * Retrieves all properties (including private ones), from object and all its ancestors.
     *
     * Standard \ReflectionClass->getProperties() does not return private properties from ancestor classes.
     *
     * @author muratyaman@gmail.com
     * @see http://php.net/manual/en/reflectionclass.getproperties.php
     *
     * @param \ReflectionClass $ref
     * @return \ReflectionProperty[]
     */
    public static function getProperties(\ReflectionClass $ref)
    {
        $isDoctrineProxy = false;
        if (
            interface_exists('Doctrine\Common\Persistence\Proxy') &&
            $ref->implementsInterface('Doctrine\Common\Persistence\Proxy')
        ) {
            $isDoctrineProxy = true;
        }

        $props = $ref->getProperties();
        $propsArr = array();

        foreach ($props as $prop) {
            if ($isDoctrineProxy && !$ref->getParentClass()->hasProperty($prop->getName())) {
                continue;
            }

            $f = $prop->getName();
            $propsArr[$f] = $prop;
        }

        if ($parentClass = $ref->getParentClass()) {
            $parentPropsArr = self::getProperties($parentClass);
            if (count($parentPropsArr) > 0) {
                $propsArr = array_merge($parentPropsArr, $propsArr);
            }
        }
        return $propsArr;
    }
}
