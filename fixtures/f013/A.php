<?php

namespace DeepCopy\f013;

use Doctrine\Persistence\Proxy;

class A implements Proxy
{
    public $foo = 1;

    /**
     * @inheritdoc
     */
    public function __load()
    {
    }

    /**
     * @inheritdoc
     */
    public function __isInitialized()
    {
    }
}
