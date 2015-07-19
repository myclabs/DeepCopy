<?php

namespace DeepCopyTest\Filter\Doctrine;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineEmptyCollectionFilter;
use DeepCopy\Matcher\PropertyMatcher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Test Doctrine Collection filter
 */
class DoctrineEmptyCollectionFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $object = new \StdClass();

        $collection = new ArrayCollection();
        $collection->add(new \StdClass());

        $object->foo = $collection;

        $filter = new DoctrineEmptyCollectionFilter();
        $filter->apply($object, 'foo', function($item){ return null; });

        $this->assertTrue($object->foo instanceof Collection);
        $this->assertNotSame($collection, $object->foo);
        $this->assertTrue($object->foo->isEmpty());
    }

    public function testIntegration()
    {
        //Prepare object to copy
        $doctrineEmptyCollectionFixture = new \StdClass();
        $originalCollection = new ArrayCollection();
        $originalCollection->add(new \StdClass());
        $doctrineEmptyCollectionFixture->foo = $originalCollection;

        //Copy
        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(new DoctrineEmptyCollectionFilter(), new PropertyMatcher(get_class($doctrineEmptyCollectionFixture), 'foo'));
        $copied = $deepCopy->copy($doctrineEmptyCollectionFixture);

        //Check result
        $this->assertTrue($copied->foo instanceof Collection);
        $this->assertNotSame($originalCollection, $copied->foo);
        $this->assertTrue($copied->foo->isEmpty());
    }
}
