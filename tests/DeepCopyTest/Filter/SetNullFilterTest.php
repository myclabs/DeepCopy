<?php

namespace DeepCopyTest\Filter;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\SetNullFilter;
use DeepCopy\Matcher\PropertyMatcher;
use ReflectionProperty;

/**
 * Test SetNull filter
 */
class SetNullFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $filter = new SetNullFilter();
        $object = new SetNullFilterTestFixture();
        $object->property1 = 'bar';
        $object->property2 = 'bam';
        $filter->apply($object, new ReflectionProperty('DeepCopyTest\Filter\SetNullFilterTestFixture', 'property1'), null);

        $this->assertNull($object->property1);
        $this->assertEquals('bam', $object->property2);
    }

    public function testIntegration()
    {
        $o = new SetNullFilterTestFixture();
        $o->property1 = new \stdClass();

        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(new SetNullFilter(), new PropertyMatcher(get_class($o), 'property1'));
        /** @var SetNullFilterTestFixture $new */
        $new = $deepCopy->copy($o);

        $this->assertNull($new->property1);
    }
}

class SetNullFilterTestFixture
{
    public $property1;
    public $property2;
}
