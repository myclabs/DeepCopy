<?php declare(strict_types=1);

namespace DeepCopyTest\Matcher\Doctrine;

use BadMethodCallException;
use DeepCopy\Matcher\Doctrine\DoctrineProxyMatcher;
use Doctrine\Common\Persistence\Proxy;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @covers \DeepCopy\Matcher\Doctrine\DoctrineProxyMatcher
 */
class DoctrineProxyMatcherTest extends TestCase
{
    /**
     * @dataProvider providePairs
     */
    public function test_it_matches_the_given_objects($object, $expected)
    {
        $matcher = new DoctrineProxyMatcher();

        $actual = $matcher->matches($object, new ReflectionProperty($object, 'foo'));

        $this->assertEquals($expected, $actual);
    }

    public function providePairs()
    {
        return [
            [new FooProxy(), true],
            [
                new class {
                    public $foo;
                },
                false
            ],
        ];
    }
}

class FooProxy implements Proxy
{
    public $foo;

    /**
     * @inheritdoc
     */
    public function __load()
    {
        throw new BadMethodCallException();
    }

    /**
     * @inheritdoc
     */
    public function __isInitialized()
    {
        throw new BadMethodCallException();
    }
}
