<?php declare(strict_types=1);

namespace DeepCopy\f005;

class Foo
{
    public $cloned = false;

    public function __clone()
    {
        $this->cloned = true;
    }
}
