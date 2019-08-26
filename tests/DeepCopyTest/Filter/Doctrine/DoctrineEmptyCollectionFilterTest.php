<?php declare(strict_types=1);

namespace DeepCopyTest\Filter\Doctrine;

use DeepCopy\Filter\Doctrine\DoctrineEmptyCollectionFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DeepCopy\Filter\Doctrine\DoctrineEmptyCollectionFilter
 */
class DoctrineEmptyCollectionFilterTest extends TestCase
{
    public function test_it_sets_the_object_property_to_an_empty_doctrine_collection()
    {
        $object = new stdClass();

        $collection = new ArrayCollection();
        $collection->add(new stdClass());

        $object->foo = $collection;

        $filter = new DoctrineEmptyCollectionFilter();

        $filter->apply(
            $object,
            'foo',
            function($item) {
                return null;
            }
        );

        $this->assertInstanceOf(Collection::class, $object->foo);
        $this->assertNotSame($collection, $object->foo);
        $this->assertTrue($object->foo->isEmpty());
    }
}
