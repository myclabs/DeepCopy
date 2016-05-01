<?php

namespace DeepCopy\TypeFilter;

class KeepFilter implements TypeFilter
{
    /**
     * {@inheritdoc}
     */
    public function apply($element)
    {
        return $element;
    }
}
