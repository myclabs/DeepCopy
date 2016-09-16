<?php
namespace DeepCopyTest\Reflection;

use DeepCopy\Reflection\ReflectionHelper;

/**
 * Test ReflectionHelper
 */
class ReflectionHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testMaintainPropertiesKey()
    {
        $child = new ReflectionHelperTestChild();
        $ref = new \ReflectionClass($child);

        $expectedProps = array(
            'childAttribute',
            'parentAttribute',
        );

        $actualProps = ReflectionHelper::getProperties($ref);
        $actualProps = array_keys($actualProps);
        sort($actualProps);

        $this->assertSame($expectedProps, $actualProps);
    }
}

class ReflectionHelperTestParent {
    public $parentAttribute;
}

class ReflectionHelperTestChild extends ReflectionHelperTestParent {
    public $childAttribute;
}
