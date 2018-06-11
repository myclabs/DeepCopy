<?php declare(strict_types=1);

namespace DeepCopy\Exception;

use UnexpectedValueException;

class CloneException extends UnexpectedValueException
{
    final public static function unclonableClass(string $class): self
    {
        return new self(
            sprintf(
                'The class "%s" is not cloneable.',
                $class
            )
        );
    }
}
