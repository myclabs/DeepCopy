<?php

namespace DeepCopy\f010;

class B
{
    public $foo = 1;

    public function __clone()
    {
        $this->foo = null;
    }
}
