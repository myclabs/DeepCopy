<?php declare(strict_types=1);

namespace DeepCopyTest\Matcher;

use DeepCopy\Matcher\PropertyMatcher;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DeepCopy\Matcher\PropertyMatcher
 */
class PropertyMatcherTest extends TestCase
{
    /**
     * @dataProvider providePairs
     */
    public function test_it_matches_the_given_objects($object, $prop, $expected)
    {
        $matcher = new PropertyMatcher(X::class, 'foo');

        $actual = $matcher->matches($object, $prop);

        $this->assertEquals($expected, $actual);
    }

    public function providePairs()
    {
        return [
            'matching case' => [new X(), 'foo', true],
            'match class, non matching prop' => [new X(), 'bar', false],
            'match class, unknown prop' => [new X(), 'unknown', false],
            'non matching class, matching prop' => [new Y(), 'unknown', false],
        ];
    }
}

class X
{
    public $foo;
    public $bar;
}

class Y
{
    public $foo;
}
