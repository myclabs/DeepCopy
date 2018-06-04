<?php declare(strict_types=1);

namespace DeepCopyTest\Matcher;

use DeepCopy\Matcher\PropertyMatcher;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

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

        $actual = $matcher->matches($object, new ReflectionProperty(X::class, $prop));

        $this->assertEquals($expected, $actual);
    }

    public function providePairs()
    {
        return [
            'matching case' => [new X(), 'foo', true],
            'match class, non matching prop' => [new X(), 'bar', false],
            'non matching class, matching prop' => [new Y(), 'bar', false],
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
