<?php declare(strict_types=1);

namespace DeepCopyTest\Matcher\Doctrine;

use BadMethodCallException;
use DeepCopy\Matcher\Doctrine\DoctrineProxyMatcher;
use Doctrine\Persistence\Proxy;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DeepCopy\Matcher\Doctrine\DoctrineProxyMatcher
 */
class DoctrineProxyMatcherTest extends TestCase
{
    #[DataProvider('providePairs')]
    public function test_it_matches_the_given_objects($object, $expected)
    {
        $matcher = new DoctrineProxyMatcher();

        $actual = $matcher->matches($object, 'unknown');

        $this->assertEquals($expected, $actual);
    }

    public static function providePairs(): array
    {
        return [
            [new FooProxy(), true],
            [new stdClass(), false],
        ];
    }
}

class FooProxy implements Proxy
{
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
