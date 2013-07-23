<?php

namespace DeepCopyTest;

use DeepCopy\Filter\SetNull;

/**
 * Test SetNull filter
 */
class SetNullTest extends \PHPUnit_Framework_TestCase
{
    public function testApplies()
    {
        $filter = new SetNull('stdClass', 'foo');
        $this->assertTrue($filter->applies(new \stdClass()));

        $filter = new SetNull('Foo', 'foo');
        $this->assertFalse($filter->applies(new \stdClass()));
    }

    public function testApply()
    {
        $filter = new SetNull('stdClass', 'foo');
        $object = new \stdClass();
        $object->foo = 'bar';
        $object->bim = 'bam';
        $filter->apply($object);

        $this->assertNull($object->foo);
        $this->assertEquals('bam', $object->bim);
    }
}
