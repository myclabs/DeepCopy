<?php

namespace DeepCopyTest\Filter;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\SetNullFilter;
use DeepCopy\Matcher\PropertyMatcher;

/**
 * Test SetNull filter
 */
class SetNullFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $filter = new SetNullFilter();
        $object = new \stdClass();
        $object->foo = 'bar';
        $object->bim = 'bam';
        $filter->apply($object, 'foo', null);

        $this->assertNull($object->foo);
        $this->assertEquals('bam', $object->bim);
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
}
