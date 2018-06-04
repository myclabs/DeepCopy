<?php declare(strict_types=1);

namespace DeepCopy\Filter;

use ReflectionProperty;

/**
 * Filter to apply to a property while copying an object
 */
interface Filter
{
    public function apply(object $object, ReflectionProperty $reflectionProperty, callable $objectCopier): void;
}
