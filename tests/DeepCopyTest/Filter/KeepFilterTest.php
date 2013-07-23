<?php

namespace DeepCopyTest\Filter;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\KeepFilter;
use DeepCopy\Matcher\PropertyMatcher;

/**
 * Test Keep filter
 */
class KeepFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $object = new \stdClass();
        $keepObject = new \stdClass();
        $object->foo = $keepObject;

        $filter = new KeepFilter();
        $filter->apply($object, 'foo', null);

        $this->assertSame($keepObject, $object->foo);
    }

    public function testIntegration()
    {
        $o = new KeepFilterTestFixture();
        $o->property1 = new \stdClass();

        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(new KeepFilter(), new PropertyMatcher(get_class($o), 'property1'));
        /** @var KeepFilterTestFixture $new */
        $new = $deepCopy->copy($o);

        $this->assertSame($o->property1, $new->property1);
    }
}

class KeepFilterTestFixture
{
    public $property1;
}
