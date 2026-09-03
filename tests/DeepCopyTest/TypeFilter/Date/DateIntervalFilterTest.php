<?php declare(strict_types=1);

namespace DeepCopyTest\TypeFilter\Date;

use DateInterval;
use DateTimeImmutable;
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

    public function test_it_deep_copies_a_DateInterval_created_from_a_date_string()
    {
        $object = DateInterval::createFromDateString('2 days');

        $filter = new DateIntervalFilter();

        $copy = $filter->apply($object);

        // The copy has to shift a date by the same amount as the original.
        $base = new DateTimeImmutable('2026-01-01 00:00:00');

        $this->assertSame(
            $base->add($object)->format('Y-m-d H:i:s'),
            $base->add($copy)->format('Y-m-d H:i:s')
        );

        $this->assertEquals($object, $copy);
        $this->assertNotSame($object, $copy);
    }

    public function test_it_deep_copies_an_inverted_DateInterval_with_microseconds()
    {
        $object = new DateInterval('PT1S');
        $object->invert = 1;
        $object->f = 0.25;

        $filter = new DateIntervalFilter();

        $copy = $filter->apply($object);

        $this->assertEquals($object, $copy);
        $this->assertNotSame($object, $copy);

        $base = new DateTimeImmutable('2026-01-01 00:00:00');

        $this->assertSame(
            $base->add($object)->format('Y-m-d H:i:s.u'),
            $base->add($copy)->format('Y-m-d H:i:s.u')
        );
    }
}
