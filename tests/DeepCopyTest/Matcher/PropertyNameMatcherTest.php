<?php declare(strict_types=1);

namespace DeepCopyTest\Matcher;

use DeepCopy\Matcher\PropertyNameMatcher;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @covers \DeepCopy\Matcher\PropertyNameMatcher
 */
class PropertyNameMatcherTest extends TestCase
{
    /**
     * @dataProvider providePairs
     */
    public function test_it_matches_the_given_property($prop, $expected)
    {
        $object = new class {
            public $foo;
            public $bar;
        };

        $matcher = new PropertyNameMatcher('foo');

        $actual = $matcher->matches($object, new ReflectionProperty($object, $prop));

        $this->assertEquals($expected, $actual);
    }

    public function providePairs()
    {
        return [
            ['foo', true],
            ['bar', false],
        ];
    }
}
