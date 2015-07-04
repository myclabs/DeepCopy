<?php

namespace DeepCopyTest\Filter\Doctrine;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineCollectionFilter;
use DeepCopy\Matcher\PropertyMatcher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Test Doctrine Collection filter
 */
class DoctrineCollectionFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $object = new \stdClass();
        $oldCollection = new ArrayCollection();
        $oldCollection->add(new \stdClass());
        $object->foo = $oldCollection;

        $filter = new DoctrineCollectionFilter();
        $filter->apply($object, 'foo', function($item) {
                return null;
            });

        $this->assertTrue($object->foo instanceof Collection);
        $this->assertNotSame($oldCollection, $object->foo);
        $this->assertCount(1, $object->foo);
    }

    public function testIntegration()
    {
        $o = new DoctrineCollectionFilterTestFixture();
        $oldCollection = new ArrayCollection();
        $oldCollectionItem = new \stdClass();
        $oldCollection->add($oldCollectionItem);
        $o->property1 = $oldCollection;

        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(new DoctrineCollectionFilter(), new PropertyMatcher(get_class($o), 'property1'));
        /** @var DoctrineCollectionFilterTestFixture $new */
        $new = $deepCopy->copy($o);

        $this->assertTrue($new->property1 instanceof Collection);
        $this->assertNotSame($oldCollection, $new->property1);
        $this->assertCount(1, $new->property1);
        $newItem = $new->property1[0];
        $this->assertNotSame($oldCollectionItem, $newItem);
    }
}

class DoctrineCollectionFilterTestFixture
{
    public $property1;
}
