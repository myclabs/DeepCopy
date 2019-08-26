<?php declare(strict_types=1);

namespace DeepCopyTest\Filter;

use DeepCopy\Filter\KeepFilter;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DeepCopy\Filter\KeepFilter
 */
class KeepFilterTest extends TestCase
{
    public function test_it_does_not_change_the_filtered_object_property()
    {
        $object = new stdClass();
        $keepObject = new stdClass();
        $object->foo = $keepObject;

        $filter = new KeepFilter();

        $filter->apply($object, 'foo', null);

        $this->assertSame($keepObject, $object->foo);
    }
}
