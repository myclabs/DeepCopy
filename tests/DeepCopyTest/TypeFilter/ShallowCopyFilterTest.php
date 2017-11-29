<?php

namespace DeepCopyTest\TypeFilter;

use DeepCopy\TypeFilter\ShallowCopyFilter;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DeepCopy\TypeFilter\ShallowCopyFilter
 */
class ShallowCopyFilterTest extends TestCase
{
    public function test_it_shallow_copies_the_given_object()
    {
        $foo = new stdClass();
        $bar = new stdClass();

        $foo->bar = $bar;

        $filter = new ShallowCopyFilter();

        $newFoo = $filter->apply($foo);

        $this->assertNotSame($foo, $newFoo);
        $this->assertSame($foo->bar, $newFoo->bar);
    }
}
