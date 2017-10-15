<?php

namespace DeepCopyTest\TypeFilter;

use DeepCopy\TypeFilter\ReplaceFilter;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @covers \DeepCopy\TypeFilter\ReplaceFilter
 */
class ReplaceFilterTest extends PHPUnit_Framework_TestCase
{
    public function test_it_returns_the_callback_called_with_the_given_object()
    {
        $foo = new stdClass();

        $callback = function ($object) {
            $object = new stdClass();
            $object->callback = true;

            return $object;
        };

        $filter = new ReplaceFilter($callback);

        $newFoo = $filter->apply($foo);

        $this->assertNotSame($newFoo, $foo);
        $this->assertTrue($newFoo->callback);
    }
}
