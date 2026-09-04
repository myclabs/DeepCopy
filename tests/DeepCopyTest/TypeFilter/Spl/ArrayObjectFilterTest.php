<?php declare(strict_types=1);

namespace DeepCopyTest\TypeFilter\Spl;

use ArrayObject;
use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\Spl\ArrayObjectFilter;
use PHPUnit\Framework\TestCase;
use RecursiveArrayIterator;
use stdClass;

/**
 * @author Dominic Tubach <dominic.tubach@to.com>
 *
 * @covers \DeepCopy\TypeFilter\Spl\ArrayObjectFilter
 */
final class ArrayObjectFilterTest extends TestCase
{
    /**
     * @var ArrayObjectFilter
     */
    private $arrayObjectFilter;

    protected function setUp(): void
    {
        $this->arrayObjectFilter = new ArrayObjectFilter(new DeepCopy());
    }

    public function test_it_deep_copies_an_array_object(): void
    {
        $foo = new stdClass();
        $foo->bar = 'baz';

        $arrayObject = new ArrayObject(
            ['foo' => $foo],
            ArrayObject::ARRAY_AS_PROPS,
            RecursiveArrayIterator::class
        );

        /** @var ArrayObject $newArrayObject */
        $newArrayObject = $this->arrayObjectFilter->apply($arrayObject);

        $this->assertNotSame($foo, $newArrayObject['foo']);
        $this->assertEquals($foo, $newArrayObject['foo']);
        $this->assertSame(ArrayObject::ARRAY_AS_PROPS, $newArrayObject->getFlags());
        $this->assertSame(RecursiveArrayIterator::class, $newArrayObject->getIteratorClass());
    }

    public function test_it_keeps_objects_shared_inside_the_array_object_shared(): void
    {
        $shared = new stdClass();
        $arrayObject = new ArrayObject(['a' => $shared, 'b' => $shared]);

        /** @var ArrayObject $newArrayObject */
        $newArrayObject = $this->arrayObjectFilter->apply($arrayObject);

        $this->assertNotSame($shared, $newArrayObject['a']);
        $this->assertSame($newArrayObject['a'], $newArrayObject['b']);
    }
}
