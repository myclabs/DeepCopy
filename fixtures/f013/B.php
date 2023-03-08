<?php

namespace DeepCopy\f013;

use Doctrine\Persistence\Proxy;

class B implements Proxy
{
    private $foo;

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

    public function getFoo()
    {
        return $this->foo;
    }

    public function setFoo($foo)
    {
        $this->foo = $foo;
    }
}
