<?php

namespace DeepCopy\TypeMatcher;

class TypeMatcher
{
    private string $type;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param mixed $element
     *
     * @return boolean
     */
    public function matches(mixed $element)
    {
        return is_object($element) ? is_a($element, $this->type) : gettype($element) === $this->type;
    }
}
