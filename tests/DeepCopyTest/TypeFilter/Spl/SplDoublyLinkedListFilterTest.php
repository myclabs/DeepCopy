<?php

namespace DeepCopyTest\TypeFilter\Spl;

use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\Spl\SplDoublyLinkedListFilter;
use PHPUnit_Framework_TestCase;
use SplDoublyLinkedList;
use stdClass;

/**
 * @covers \DeepCopy\TypeFilter\Spl\SplDoublyLinkedListFilter
 */
class SplDoublyLinkedListFilterTest extends PHPUnit_Framework_TestCase
{
    public function test_it_deeps_copy_a_doubly_linked_spl_list()
    {
        $foo = new stdClass();

        $list = new SplDoublyLinkedList();
        $list->push($foo);

        $filter = new SplDoublyLinkedListFilter(new FakeDeepCopy());

        $newList = $filter->apply($list);

        $this->assertSame(1, count($newList));
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