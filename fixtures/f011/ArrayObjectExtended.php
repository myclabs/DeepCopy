<?php declare(strict_types=1);

namespace DeepCopy\f011;

use ArrayObject;

class ArrayObjectExtended extends ArrayObject
{
    public $x;

    public function __construct($x)
    {
        parent::__construct();
        $this->x = $x;
    }
}
