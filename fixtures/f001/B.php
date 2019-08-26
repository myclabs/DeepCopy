<?php declare(strict_types=1);

namespace DeepCopy\f001;

class B extends A
{
    private $bProp;

    public function getBProp()
    {
        return $this->bProp;
    }

    public function setBProp($prop)
    {
        $this->bProp = $prop;

        return $this;
    }
}
