<?php

namespace DeepCopyTest;

use DeepCopy\DeepCopy;

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
        $o = new A();
        $o->property1 = new B();
        $o->property2 = new B();
        $o->property1->property = $o->property2;

        $deepCopy = new DeepCopy();
        /** @var A $new */
        $new = $deepCopy->copy($o);

        $this->assertDeepCopyOf($o, $new);

        $this->assertSame($new->property1->property, $o->property2);
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
        $deepCopy->addFilter(get_class($o), 'property1', $filter);
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
        $deepCopy->addFilter(get_class($o), 'property1', $filter);
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
