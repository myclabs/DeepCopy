<?php declare(strict_types=1);

namespace DeepCopyTest\TypeFilter\Date;

use DateInterval;
use DeepCopy\TypeFilter\Date\DateIntervalFilter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DeepCopy\TypeFilter\Date\DateIntervalFilter
 */
class DateIntervalFilterTest extends TestCase
{
    public function test_it_deep_copies_a_DateInterval()
    {
        $object = new DateInterval('P2D');;

        $filter = new DateIntervalFilter();

        $copy = $filter->apply($object);

        $this->assertEquals($object, $copy);
        $this->assertNotSame($object, $copy);
    }
}
