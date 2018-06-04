<?php

namespace DeepCopy\f005;

class Foo
{
    public $foo;

    public $cloned = false;

    public function __clone()
    {
        $this->cloned = true;
    }
}
