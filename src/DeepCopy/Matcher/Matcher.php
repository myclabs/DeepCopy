<?php

namespace DeepCopy\Matcher;

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
    public function matches($object, $property);
}
