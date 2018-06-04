<?php declare(strict_types=1);

namespace DeepCopy\TypeFilter\Spl;

use Closure;
use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\TypeFilter;
use SplDoublyLinkedList;

final class SplDoublyLinkedListFilter implements TypeFilter
{
    private $copier;

    public function __construct(DeepCopy $copier)
    {
        $this->copier = $copier;
    }

    /**
     * {@inheritdoc}
     *
     * @param SplDoublyLinkedList $list
     */
    public function apply($list): SplDoublyLinkedList
    {
        $newList = clone $list;

        $copy = $this->createCopyClosure();

        return $copy($newList);
    }

    private function createCopyClosure()
    {
        $copier = $this->copier;

        $copy = function (SplDoublyLinkedList $list) use ($copier): SplDoublyLinkedList {
            $newList = new SplDoublyLinkedList();

            foreach ($list as $value) {
                $newList->push($copier->recursiveCopy($value));
            }

            return $newList;
        };

        return Closure::bind($copy, null, DeepCopy::class);
    }
}
