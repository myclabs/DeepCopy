<?php declare(strict_types=1);

namespace DeepCopyTest\TypeMatcher;

use DeepCopy\TypeMatcher\TypeMatcher;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DeepCopy\TypeMatcher\TypeMatcher
 */
class TypeMatcherTest extends TestCase
{
    /**
     * @dataProvider provideElements
     */
    public function test_it_retrieves_the_object_properties($type, $element, $expected)
    {
        $matcher = new TypeMatcher($type);

        $actual = $matcher->matches($element);

        $this->assertSame($expected, $actual);
    }

    public function provideElements()
    {
        return [
            '[class] same class as type' => ['stdClass', new stdClass(), true],
            '[class] different class as type' => ['stdClass', new Foo(), false],
            '[class] child class as type' => [Foo::class, new Bar(), true],
            '[class] interface implementation as type' => [IA::class, new A(), true],

            '[scalar] array match' => ['array', [], true],
            '[scalar] array no match' => ['array', true, false],
        ];
    }
}

class Foo
{
}

class Bar extends Foo
{
}

interface IA
{
}

class A implements IA
{
}
