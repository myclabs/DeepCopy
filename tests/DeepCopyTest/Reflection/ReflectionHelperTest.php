<?php declare(strict_types=1);

namespace DeepCopyTest\Reflection;

use DeepCopy\Reflection\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \DeepCopy\Reflection\ReflectionHelper
 */
class ReflectionHelperTest extends TestCase
{
    public function test_it_retrieves_the_object_properties()
    {
        $child = new ReflectionHelperTestChild();
        $childReflectionClass = new ReflectionClass($child);

        $expectedProps = array(
            'a1',
            'a2',
            'a3',
            'a10',
            'a11',
            'a12',
            'a20',
            'a21',
            'a22',
            'a100',
            'a101',
            'a102',
        );

        $actualProps = ReflectionHelper::getProperties($childReflectionClass);

        $this->assertSame($expectedProps, array_keys($actualProps));
    }
}

class ReflectionHelperTestParent
{
    public static $a20;
    protected static $a21;
    private static $a22;
    public $a1;
    public $a10;
    protected $a2;
    protected $a11;
    private $a3;
    private $a12;
}

class ReflectionHelperTestChild extends ReflectionHelperTestParent
{
    public $a1;
    public $a100;
    protected $a2;
    protected $a101;
    private $a3;
    private $a102;
}
