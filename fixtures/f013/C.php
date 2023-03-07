<?php

namespace DeepCopy\f013;

class C
{
    public $foo = 1;

    public function __clone()
    {
        $this->foo = null;
    }
}
