<?php declare(strict_types=1);

namespace DeepCopy\TypeFilter;

interface TypeFilter
{
    /**
     * Applies the filter to the object.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function apply($value);
}
