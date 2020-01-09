<?php

namespace DeepCopy\f010;

use Doctrine\Common\Persistence\Proxy;

class A implements Proxy
{
    /** @var object */
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

    public function getFoo(): object
    {
        return $this->foo;
    }

    public function setFoo(object $foo): void
    {
        $this->foo = $foo;
    }
}
