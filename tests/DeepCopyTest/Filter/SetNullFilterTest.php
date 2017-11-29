<?php

namespace DeepCopyTest\Filter;

use DeepCopy\Filter\SetNullFilter;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DeepCopy\Filter\SetNullFilter
 */
class SetNullFilterTest extends TestCase
{
    public function test_it_sets_the_given_property_to_null()
    {
        $filter = new SetNullFilter();

        $object = new stdClass();
        $object->foo = 'bar';
        $object->bim = 'bam';

        $filter->apply($object, 'foo', null);

        $this->assertNull($object->foo);
        $this->assertEquals('bam', $object->bim);
    }
}
