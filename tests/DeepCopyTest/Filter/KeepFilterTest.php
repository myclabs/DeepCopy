<?php

namespace DeepCopyTest\Filter;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\KeepFilter;
use DeepCopy\Matcher\PropertyMatcher;
use ReflectionProperty;

/**
 * Test Keep filter
 */
class KeepFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $object = new KeepFilterTestFixture();
        $keepObject = new \stdClass();
        $object->property1 = $keepObject;

        $filter = new KeepFilter();
        $filter->apply($object, new ReflectionProperty('DeepCopyTest\Filter\KeepFilterTestFixture', 'property1'), null);

        $this->assertSame($keepObject, $object->property1);
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
