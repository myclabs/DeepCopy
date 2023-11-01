<?php declare(strict_types=1);

namespace DeepCopyTest\Filter\Doctrine;

use DeepCopy\Filter\Doctrine\DoctrineCollectionFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DeepCopy\Filter\Doctrine\DoctrineCollectionFilter
 */
class DoctrineCollectionFilterTest extends TestCase
{
    public function test_it_copies_the_object_property_array_collection()
    {
        $object = new stdClass();
        $oldCollection = new ArrayCollection();
        $oldCollection->add($stdClass = new stdClass());
        $object->foo = $oldCollection;

        $filter = new DoctrineCollectionFilter();

        $filter->apply(
            $object,
            'foo',
            function($item) {
                return null;
            }
        );

        $this->assertInstanceOf(Collection::class, $object->foo);
        $this->assertNotSame($oldCollection, $object->foo);
        $this->assertCount(1, $object->foo);

        $objectOfNewCollection = $object->foo->get(0);

        $this->assertNotSame($stdClass, $objectOfNewCollection);
    }

    public function test_it_ignores_objects_of_classes_when_instructed()
    {
        $object = new stdClass();
        $oldCollection = new ArrayCollection();
        $oldCollection->add($stdClass = new stdClass());
        $oldCollection->add(new \DateTimeImmutable()); // should not be copied to the new collection
        $oldCollection->add(new \DateTime()); // should not be copied to the new collection
        $object->foo = $oldCollection;

        $this->assertCount(3, $object->foo);

        $filter = new DoctrineCollectionFilter([\DateTimeInterface::class]); // <-- here is why

        $filter->apply(
            $object,
            'foo',
            function($item) {
                return null;
            }
        );

        $this->assertInstanceOf(Collection::class, $object->foo);
        $this->assertNotSame($oldCollection, $object->foo);
        $this->assertCount(1, $object->foo);

        $objectOfNewCollection = $object->foo->get(0);

        $this->assertNotSame($stdClass, $objectOfNewCollection);
    }
}
