<?php

namespace DeepCopyTest\Matcher;

use DeepCopy\Matcher\PropertyNameMatcher;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DeepCopy\Matcher\PropertyNameMatcher
 */
class PropertyNameMatcherTest extends TestCase
{
    /**
     * @dataProvider providePairs
     */
    public function test_it_matches_the_given_property($object, $prop, $expected)
    {
        $matcher = new PropertyNameMatcher('foo');

        $actual = $matcher->matches($object, $prop);

        $this->assertEquals($expected, $actual);
    }

    public function providePairs()
    {
        return [
            [new stdClass(), 'foo', true],
            [new stdClass(), 'unknown', false],
        ];
    }
}
