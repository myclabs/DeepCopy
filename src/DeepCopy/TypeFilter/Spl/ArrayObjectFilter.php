<?php
namespace DeepCopy\TypeFilter\Spl;

use Closure;
use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\TypeFilter;

/**
 * In PHP 7.4 the storage of an ArrayObject isn't returned as
 * ReflectionProperty. So we deep copy its array copy.
 */
final class ArrayObjectFilter implements TypeFilter
{
    private DeepCopy $copier;

    public function __construct(DeepCopy $copier)
    {
        $this->copier = $copier;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(mixed $arrayObject)
    {
        $clone = clone $arrayObject;
        $copy = $this->createCopyClosure();

        foreach ($arrayObject->getArrayCopy() as $k => $v) {
            $clone->offsetSet($k, $copy($v));
        }

        return $clone;
    }

    /**
     * Copies through the recursive entry point rather than {@see DeepCopy::copy()},
     * which would reset the map of already copied objects: shared objects would
     * be duplicated and a cycle running through the ArrayObject would not
     * terminate. Same approach as {@see SplDoublyLinkedListFilter}.
     */
    private function createCopyClosure(): Closure
    {
        $copier = $this->copier;

        $copy = function (mixed $value) use ($copier): mixed {
            return $copier->recursiveCopy($value);
        };

        return Closure::bind($copy, null, DeepCopy::class);
    }
}

