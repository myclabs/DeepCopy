<?php

namespace DeepCopy\f009;

use Doctrine\Common\Persistence\Proxy;

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
