<?php

namespace DeepCopyTest;

use DeepCopy\Filter\KeepFilter;

/**
 * Test Keep filter
 */
class KeepFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $filter = new KeepFilter('stdClass', 'foo');
        $object = new \stdClass();
        $keepObject = new \stdClass();
        $object->foo = $keepObject;

        $filter->apply($object, 'foo');

        $this->assertSame($keepObject, $object->foo);
    }
}
