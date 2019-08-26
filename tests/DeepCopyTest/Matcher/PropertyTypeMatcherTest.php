<?php declare(strict_types=1);

namespace DeepCopyTest\Matcher;

use DeepCopy\Matcher\PropertyTypeMatcher;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DeepCopy\Matcher\PropertyTypeMatcher
 */
class PropertyTypeMatcherTest extends TestCase
{
    /**
     * @dataProvider providePairs
     */
    public function test_it_matches_the_given_property($object, $expected)
    {
        $matcher = new PropertyTypeMatcher(PropertyTypeMatcherTestFixture2::class);

        $actual = $matcher->matches($object, 'foo');

        $this->assertEquals($expected, $actual);
    }

    public function providePairs()
    {
        $object1 = new PropertyTypeMatcherTestFixture1();
        $object1->foo = new PropertyTypeMatcherTestFixture2();

        $object2 = new PropertyTypeMatcherTestFixture1();
        $object2->foo = new stdClass();

        $object3 = new PropertyTypeMatcherTestFixture1();
        $object3->foo = true;

        return [
            [new PropertyTypeMatcherTestFixture1(), false],
            [$object1, true],
            [$object2, false],
            [$object3, false],
            [new stdClass(), false],
        ];
    }
}

class PropertyTypeMatcherTestFixture1
{
    public $foo;
}

class PropertyTypeMatcherTestFixture2
{
}
