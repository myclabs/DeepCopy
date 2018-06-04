<?php declare(strict_types=1);

namespace DeepCopy\Matcher;

use ReflectionProperty;

interface Matcher
{
    public function matches(object $object, ReflectionProperty $reflectionProperty): bool;
}
