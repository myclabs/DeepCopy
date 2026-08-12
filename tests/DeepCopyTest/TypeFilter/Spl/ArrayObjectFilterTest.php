<?php declare(strict_types=1);

namespace DeepCopyTest\TypeFilter\Spl;

use ArrayObject;
use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\Spl\ArrayObjectFilter;
use PHPUnit\Framework\TestCase;
use RecursiveArrayIterator;

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

    /** @var DeepCopy */
    private $copier;

    protected function setUp(): void
    {
        $this->copier = $this->createMock(DeepCopy::class);
        $this->arrayObjectFilter = new ArrayObjectFilter($this->copier);
    }

    public function test_it_deep_copies_an_array_object(): void
    {
        $arrayObject = new ArrayObject(['foo' => 'bar'], ArrayObject::ARRAY_AS_PROPS, RecursiveArrayIterator::class);
        $this->copier->expects($this->once())->method('copy')->with('bar')->willReturn('baz');

        /** @var \ArrayObject $newArrayObject */
        $newArrayObject = $this->arrayObjectFilter->apply($arrayObject);
        $this->assertSame(['foo' => 'baz'], $newArrayObject->getArrayCopy());
        $this->assertSame(ArrayObject::ARRAY_AS_PROPS, $newArrayObject->getFlags());
        $this->assertSame(RecursiveArrayIterator::class, $newArrayObject->getIteratorClass());
    }
}
