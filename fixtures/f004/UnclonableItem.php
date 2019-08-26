<?php declare(strict_types=1);

namespace DeepCopy\f004;

use BadMethodCallException;

class UnclonableItem
{
    private function __clone()
    {
        throw new BadMethodCallException('Unsupported call.');
    }
}
