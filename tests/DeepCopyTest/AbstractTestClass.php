<?php

namespace DeepCopyTest;

/**
 * Abstract test class
 */
abstract class AbstractTestClass extends \PHPUnit_Framework_TestCase
{
    protected function assertDeepCopyOf($expected, $actual)
    {
        if (is_null($expected)) {
            $this->assertNull($actual);
            return;
        }

        $this->assertInternalType(gettype($expected), $actual);

        if (is_array($expected)) {
            $this->assertInternalType('array', $actual);
            $this->assertCount(count($expected), $actual);
            foreach ($actual as $i => $item) {
                $this->assertDeepCopyOf($expected[$i], $item);
            }
            return;
        }

        if (!is_object($expected)) {
            $this->assertSame($expected, $actual);
            return;
        }

        $this->assertNotSame($expected, $actual);
        $this->assertInstanceOf(get_class($expected), $actual);

        $expectedProperties = (array) $expected;
        $actualProperties = (array) $actual;

        $this->assertSame(array_keys($expectedProperties), array_keys($actualProperties));
        foreach ($expectedProperties as $name => $value) {
            $this->assertDeepCopyOf($value, $actualProperties[$name]);
        }

        if ($expected instanceof \SplDoublyLinkedList) {
            /** @var \SplDoublyLinkedList $actual */
            $this->assertSame($expected->count(), $actual->count());

            while (!$expected->isEmpty() && !$actual->isEmpty()) {
                $this->assertDeepCopyOf($expected->pop(), $actual->pop());
            }
        }
    }
}
