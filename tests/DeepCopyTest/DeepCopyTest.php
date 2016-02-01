<?php

namespace DeepCopyTest;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\Filter;
use DeepCopy\Filter\ReplaceFilter;
use DeepCopy\Matcher\PropertyMatcher;
use DeepCopy\Matcher\PropertyTypeMatcher;
use DeepCopy\TypeFilter\TypeFilter;
use DeepCopy\TypeMatcher\TypeMatcher;
use ReflectionProperty;

/**
 * DeepCopyTest
 */
class DeepCopyTest extends AbstractTestClass
{
    public function testSimpleObjectCopy()
    {
        $o = new A();

        $deepCopy = new DeepCopy();

        $this->assertDeepCopyOf($o, $deepCopy->copy($o));
    }

    public function testPropertyScalarCopy()
    {
        $o = new A();
        $o->property1 = 'foo';

        $deepCopy = new DeepCopy();

        $this->assertDeepCopyOf($o, $deepCopy->copy($o));
    }

    public function testPropertyObjectCopy()
    {
        $o = new A();
        $o->property1 = new B();

        $deepCopy = new DeepCopy();

        $this->assertDeepCopyOf($o, $deepCopy->copy($o));
    }

    public function testPrivatePropertyOfParentObjectCopy()
    {
        $o = new E();
        $o->setProperty1(new B);
        $o->setProperty2(new B);

        $deepCopy = new DeepCopy();

        $this->assertDeepCopyOf($o, $deepCopy->copy($o));
    }

    public function testPropertyArrayCopy()
    {
        $o = new A();
        $o->property1 = [new B()];

        $deepCopy = new DeepCopy();

        $this->assertDeepCopyOf($o, $deepCopy->copy($o));
    }

    public function testCycleCopy1()
    {
        $a = new A();
        $b = new B();
        $c = new B();
        $a->property1 = $b;
        $a->property2 = $c;
        $b->property = $c;

        $deepCopy = new DeepCopy();
        /** @var A $a2 */
        $a2 = $deepCopy->copy($a);

        $this->assertDeepCopyOf($a, $a2);

        $this->assertSame($a2->property1->property, $a2->property2);
    }

    public function testCycleCopy2()
    {
        $a = new B();
        $b = new B();
        $a->property = $b;
        $b->property = $a;

        $deepCopy = new DeepCopy();
        /** @var B $a2 */
        $a2 = $deepCopy->copy($a);

        $this->assertSame($a2, $a2->property->property);
    }

    /**
     * Dynamic properties should be cloned
     */
    public function testDynamicProperties()
    {
        $a = new \stdClass();
        $a->b = new \stdClass();

        $deepCopy = new DeepCopy();
        $a2 = $deepCopy->copy($a);
        $this->assertNotSame($a->b, $a2->b);
        $this->assertDeepCopyOf($a, $a2);
    }

    public function testNonClonableItems()
    {
        $a = new \ReflectionClass('DeepCopyTest\A');
        $deepCopy = new DeepCopy();
        $a2 = $deepCopy->skipUncloneable()->copy($a);
        $this->assertSame($a, $a2);
    }

    /**
     * @expectedException \DeepCopy\Exception\CloneException
     * @expectedExceptionMessage Class "DeepCopyTest\C" is not cloneable.
     */
    public function testCloneException()
    {
        $o = new C;
        $deepCopy = new DeepCopy();
        $deepCopy->copy($o);
    }

    /**
     * @test
     */
    public function filtersShouldBeApplied()
    {
        $o = new A();
        $o->property1 = 'foo';

        $filter = $this->getMockForAbstractClass('DeepCopy\Filter\Filter');
        $filter->expects($this->once())
            ->method('apply')
            ->will($this->returnCallback(function($object, ReflectionProperty $reflectionProperty) {
                        $reflectionProperty->setValue($object, null);
                    }));

        $deepCopy = new DeepCopy();
        $deepCopy->addFilter($filter, new PropertyMatcher(get_class($o), 'property1'));
        /** @var A $new */
        $new = $deepCopy->copy($o);

        $this->assertNull($new->property1);
    }

    /**
     * If a filter applies to a property, the property shouldn't be copied
     * @test
     */
    public function filtersShouldBeAppliedAndBreakPropertyCopying()
    {
        $o = new A();
        $o->property1 = new B();

        /* @var Filter|\PHPUnit_Framework_MockObject_MockObject $filter */
        $filter = $this->getMockForAbstractClass('DeepCopy\Filter\Filter');
        $filter->expects($this->once())
            ->method('apply')
            ->will($this->returnCallback(function($object, ReflectionProperty $reflectionProperty, $objectCopier) {
                    }));

        $deepCopy = new DeepCopy();
        $deepCopy->addFilter($filter, new PropertyMatcher(get_class($o), 'property1'));
        /** @var A $new */
        $new = $deepCopy->copy($o);

        $this->assertSame($o->property1, $new->property1);
    }

    /**
     * If a filter applies to an object, it should not be copied
     */
    public function testTypeFilterShouldBeAppliedOnObject()
    {
        $o = new A();
        $o->property1 = new B();

        /* @var TypeFilter|\PHPUnit_Framework_MockObject_MockObject $filter */
        $filter = $this->getMockForAbstractClass('DeepCopy\TypeFilter\TypeFilter');
        $filter->expects($this->once())
            ->method('apply')
            ->will($this->returnValue(null));

        $deepCopy = new DeepCopy();
        $deepCopy->addTypeFilter($filter, new TypeMatcher('DeepCopyTest\B'));
        /** @var A $new */
        $new = $deepCopy->copy($o);

        $this->assertNull($new->property1);
    }

    /**
     * If a filter applies to an array member, it should not be copied
     */
    public function testTypeFilterShouldBeAppliedOnArrayMember()
    {
        $arr = [new A, new A, new B, new B, new A];

        /* @var TypeFilter|\PHPUnit_Framework_MockObject_MockObject $filter */
        $filter = $this->getMockForAbstractClass('DeepCopy\TypeFilter\TypeFilter');
        $filter->expects($this->exactly(2))
            ->method('apply')
            ->will($this->returnValue(null));

        $deepCopy = new DeepCopy();
        $deepCopy->addTypeFilter($filter, new TypeMatcher('DeepCopyTest\B'));
        /** @var A $new */
        $new = $deepCopy->copy($arr);

        $this->assertNull($new[2]);
        $this->assertNull($new[3]);
    }

    public function testPrivatePropertyOfParentObjectCopyWithFiltersAndMatchers()
    {
        $o = new E;
        $o->setProperty1(new B);

        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(new ReplaceFilter(function() {return 'foo';}), new PropertyTypeMatcher('DeepCopyTest\B'));

        $new = $deepCopy->copy($o);

        $this->assertSame('foo', $new->getProperty1());
    }
}

class A
{
    public $property1;
    public $property2;
}

class B
{
    public $property;
}

class C
{
    private function __clone(){}
}

class D
{
    private $property1;

    public function getProperty1()
    {
        return $this->property1;
    }

    public function setProperty1($property1)
    {
        $this->property1 = $property1;
        return $this;
    }
}

class E extends D
{
    private $property2;

    public function getProperty2()
    {
        return $this->property2;
    }

    public function setProperty2($property2)
    {
        $this->property2 = $property2;
        return $this;
    }
}
