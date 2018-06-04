<?php declare(strict_types=1);

namespace DeepCopy\TypeMatcher;

use function gettype;
use function is_a;
use function is_object;

/**
 * Checks that the given value matches the configured type.
 */
final class TypeMatcher
{
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function matches($value): bool
    {
        return is_object($value) ? is_a($value, $this->type) : gettype($value) === $this->type;
    }
}
