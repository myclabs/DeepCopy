<?php

namespace DeepCopy\Matcher;

use ReflectionProperty;

/**
 * Matcher interface
 */
interface Matcher
{
    /**
     * @param object $object
     * @param string $property
     * @return boolean
     */
    public function matches($object, ReflectionProperty $reflectionProperty);
}
