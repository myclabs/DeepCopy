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
        $o->property1 = array(new B());

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
