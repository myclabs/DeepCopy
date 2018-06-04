<?php declare(strict_types=1);

namespace DeepCopyTest\Filter\Doctrine;

use BadMethodCallException;
use DeepCopy\Filter\Doctrine\DoctrineProxyFilter;
use Doctrine\Common\Persistence\Proxy;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @covers \DeepCopy\Filter\Doctrine\DoctrineProxyFilter
 */
class DoctrineProxyFilterTest extends TestCase
{
    public function test_it_loads_the_doctrine_proxy()
    {
        $foo = new Foo();

        $filter = new DoctrineProxyFilter();

        $filter->apply(
            $foo,
            new ReflectionProperty($foo, 'bar'),
            function($item) {
                throw new BadMethodCallException('Did not expect to be called.');
            }
        );

        $this->assertTrue($foo->isLoaded);
    }
}

class Foo implements Proxy
{
    public $bar;

    public $isLoaded = false;

    public function __load()
    {
        $this->isLoaded = true;
    }

    public function __isInitialized()
    {
    }
}
