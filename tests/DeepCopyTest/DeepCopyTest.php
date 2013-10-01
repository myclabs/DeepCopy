<?php

namespace DeepCopyTest;

use DeepCopy\DeepCopy;
use DeepCopy\Matcher\PropertyMatcher;

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

    public function testPropertyArrayCopy()
    {
        $o = new A();
        $o->property1 = [new B()];

        $deepCopy = new DeepCopy();

        $this->assertDeepCopyOf($o, $deepCopy->copy($o));
    }

    public function testCycleCopy()
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
            ->will($this->returnCallback(function($object, $property, $objectCopier) {
                        $object->$property = null;
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

        $filter = $this->getMockForAbstractClass('DeepCopy\Filter\Filter');
        $filter->expects($this->once())
            ->method('apply')
            ->will($this->returnCallback(function($object, $property, $objectCopier) {
                    }));

        $deepCopy = new DeepCopy();
        $deepCopy->addFilter($filter, new PropertyMatcher(get_class($o), 'property1'));
        /** @var A $new */
        $new = $deepCopy->copy($o);

        $this->assertSame($o->property1, $new->property1);
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
