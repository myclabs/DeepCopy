<?php

namespace DeepCopy\TypeMatcher;

interface TypeMatcherInterface
{
    /**
     * @param $element
     *
     * @return boolean
     */
    public function matches($element);
}
