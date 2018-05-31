<?php

namespace DeepCopy\Matcher;

use ReflectionProperty;

interface Matcher
{
    /**
     * @param object             $object
     * @param ReflectionProperty $reflectionProperty
     *
     * @return boolean
     */
    public function matches($object, ReflectionProperty $reflectionProperty);
}
