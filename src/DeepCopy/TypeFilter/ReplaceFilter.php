<?php declare(strict_types=1);

namespace DeepCopy\TypeFilter;

final class ReplaceFilter implements TypeFilter
{
    private $callback;

    /**
     * @param callable $callable Will be called to get the new value for each element to replace
     */
    public function __construct(callable $callable)
    {
        $this->callback = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($value)
    {
        return ($this->callback)($value);
    }
}
