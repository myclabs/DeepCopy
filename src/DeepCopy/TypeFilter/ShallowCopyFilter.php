<?php declare(strict_types=1);

namespace DeepCopy\TypeFilter;

final class ShallowCopyFilter implements TypeFilter
{
    /**
     * {@inheritdoc}
     *
     * @param object $value
     */
    public function apply($value): object
    {
        return clone $value;
    }
}
