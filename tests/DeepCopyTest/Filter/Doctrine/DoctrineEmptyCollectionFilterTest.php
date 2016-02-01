<?php

namespace DeepCopyTest\Filter\Doctrine;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineEmptyCollectionFilter;
use DeepCopy\Matcher\PropertyMatcher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ReflectionProperty;

/**
 * Test Doctrine Collection filter
 */
class DoctrineEmptyCollectionFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $object = new DoctrineEmptyCollectionFilterTestFixture();

        $collection = new ArrayCollection();
        $collection->add(new \StdClass());

        $object->property1 = $collection;

        $filter = new DoctrineEmptyCollectionFilter();
        $filter->apply($object, new ReflectionProperty('DeepCopyTest\Filter\Doctrine\DoctrineEmptyCollectionFilterTestFixture', 'property1'), function($item){ return null; });

        $this->assertTrue($object->property1 instanceof Collection);
        $this->assertNotSame($collection, $object->property1);
        $this->assertTrue($object->property1->isEmpty());
    }

    public function testIntegration()
    {
        //Prepare object to copy
        $doctrineEmptyCollectionFixture = new \StdClass();
        $originalCollection = new ArrayCollection();
        $originalCollection->add(new \StdClass());
        $doctrineEmptyCollectionFixture->property1 = $originalCollection;

        //Copy
        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(new DoctrineEmptyCollectionFilter(), new PropertyMatcher(get_class($doctrineEmptyCollectionFixture), 'property1'));
        $copied = $deepCopy->copy($doctrineEmptyCollectionFixture);

        //Check result
        $this->assertTrue($copied->property1 instanceof Collection);
        $this->assertNotSame($originalCollection, $copied->property1);
        $this->assertTrue($copied->property1->isEmpty());
    }
}

class DoctrineEmptyCollectionFilterTestFixture
{
    public $property1;
}
