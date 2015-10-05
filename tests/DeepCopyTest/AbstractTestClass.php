<?php

namespace DeepCopyTest;

use DeepCopy\Reflection\ReflectionHelper;

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

        $class = new \ReflectionClass($actual);
        foreach (ReflectionHelper::getProperties($class) as $property) {
            $property->setAccessible(true);
            $this->assertDeepCopyOf($property->getValue($expected), $property->getValue($actual));
        }
    }
}
