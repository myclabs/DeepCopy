<?php

namespace DeepCopyTest\TypeFilter\Spl;

use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\Spl\SplDoublyLinkedListFilter;
use PHPUnit\Framework\TestCase;
use SplDoublyLinkedList;
use stdClass;

/**
 * @covers \DeepCopy\TypeFilter\Spl\SplDoublyLinkedListFilter
 */
class SplDoublyLinkedListFilterTest extends TestCase
{
    public function test_it_deep_copies_a_doubly_linked_spl_list()
    {
        $foo = new stdClass();

        $list = new SplDoublyLinkedList();
        $list->push($foo);

        $filter = new SplDoublyLinkedListFilter(new FakeDeepCopy());

        $newList = $filter->apply($list);

        $this->assertCount(1, $newList);
        $this->assertNotSame($foo, $newList->next());
    }
}

class FakeDeepCopy extends DeepCopy
{
    /**
     * @inheritdoc
     */
    public function copy($object)
    {
        return new stdClass();
    }
}
