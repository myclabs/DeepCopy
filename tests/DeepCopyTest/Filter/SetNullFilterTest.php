<?php

namespace DeepCopyTest;

use DeepCopy\Filter\SetNullFilter;

/**
 * Test SetNull filter
 */
class SetNullFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $filter = new SetNullFilter();
        $object = new \stdClass();
        $object->foo = 'bar';
        $object->bim = 'bam';
        $filter->apply($object, 'foo');

        $this->assertNull($object->foo);
        $this->assertEquals('bam', $object->bim);
    }
}
