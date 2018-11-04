<?php declare(strict_types=1);

namespace DeepCopy;

use DateInterval;
use DateTimeInterface;
use DateTimeZone;
use DeepCopy\Exception\CloneException;
use DeepCopy\Filter\ChainableFilter;
use DeepCopy\Filter\Filter;
use DeepCopy\Matcher\Matcher;
use DeepCopy\Reflection\ReflectionHelper;
use DeepCopy\TypeFilter\Date\DateIntervalFilter;
use DeepCopy\TypeFilter\Spl\SplDoublyLinkedListFilter;
use DeepCopy\TypeFilter\TypeFilter;
use DeepCopy\TypeMatcher\TypeMatcher;
use ReflectionObject;
use ReflectionProperty;
use SplDoublyLinkedList;
use function is_array;
use function is_object;
use function is_resource;
use function spl_object_id;

final class DeepCopy
{
    /**
     * @var object[] List of objects copied.
     */
    private $hashMap = [];

    /**
     * @var array Array of ['filter' => Filter, 'matcher' => Matcher] pairs
     */
    private $filters = [];

    /**
     * @var array Array of ['filter' => TypeFilter, 'matcher' => TypeMatcher] pairs
     */
    private $typeFilters = [];

    private $skipUncloneable;
    private $useCloneMethod;

    /**
     * @param bool $useCloneMethod           If set to true, when an object implements the __clone() function, it will
     *                                       be used instead of the regular deep cloning.
     * @param bool $skipUncloneable          If enabled, will not throw an exception when coming across an uncloneable
     *                                       property.
     * @param Array<Filter, Matcher>         List of filter-matcher pairs
     * @param Array<TypeFilter, TypeMatcher> List of type filter-matcher pairs
     */
    public function __construct(
        bool $useCloneMethod = false,
        bool $skipUncloneable = false,
        array $filters = [],
        array $typeFilters = []
    ) {
        $this->useCloneMethod = $useCloneMethod;

        foreach ($filters as [$filter, $matcher]) {
            $this->addFilter($filter, $matcher);
        }

        $typeFilters[] = [
            new DateIntervalFilter(),
            new TypeMatcher(DateInterval::class)
        ];
        $typeFilters[] = [
            new SplDoublyLinkedListFilter($this),
            new TypeMatcher(SplDoublyLinkedList::class)
        ];

        foreach ($typeFilters as [$filter, $matcher]) {
            $this->addTypeFilter($filter, $matcher);
        }

        $this->skipUncloneable = $skipUncloneable;
    }

    /**
     * Deep copies the given value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function copy($value)
    {
        $this->hashMap = [];

        return $this->recursiveCopy($value);
    }

    /**
     * @return mixed
     */
    private function recursiveCopy($value)
    {
        // Matches Type Filter
        if ($filter = $this->getFirstMatchedTypeFilter($value)) {
            return $filter->apply($value);
        }

        if (is_resource($value)) {
            return $value;
        }

        if (is_array($value)) {
            return $this->copyArray($value);
        }

        if (!is_object($value)) {
            return $value;
        }

        return $this->copyObject($value);
    }

    private function copyArray(array $array): array
    {
        foreach ($array as $key => $value) {
            $array[$key] = $this->recursiveCopy($value);
        }

        return $array;
    }

    private function copyObject(object $object): object
    {
        $objectHash = spl_object_id($object);

        if (isset($this->hashMap[$objectHash])) {
            return $this->hashMap[$objectHash];
        }

        $reflectedObject = new ReflectionObject($object);

        if (false === $reflectedObject->isCloneable()) {
            if ($this->skipUncloneable) {
                $this->hashMap[$objectHash] = $object;

                return $object;
            }

            throw CloneException::unclonableClass($reflectedObject->getName());
        }

        $newObject = clone $object;
        $this->hashMap[$objectHash] = $newObject;

        if ($this->useCloneMethod && $reflectedObject->hasMethod('__clone')) {
            return $newObject;
        }

        if ($newObject instanceof DateTimeInterface || $newObject instanceof DateTimeZone) {
            return $newObject;
        }

        foreach (ReflectionHelper::getProperties($reflectedObject) as $property) {
            $this->copyObjectProperty($newObject, $property);
        }

        return $newObject;
    }

    private function copyObjectProperty(object $object, ReflectionProperty $property): void
    {
        // Ignore static properties
        if ($property->isStatic()) {
            return;
        }

        $filterWasApplied = false;

        // Apply the filters
        foreach ($this->filters as [$matcher, $filter]) {
            /** @var Matcher $matcher */
            /** @var Filter $filter */

            if ($matcher->matches($object, $property)) {
                $filter->apply(
                    $object,
                    $property,
                    function ($object) {
                        return $this->recursiveCopy($object);
                    }
                );

                $filterWasApplied = true;

                if ($filter instanceof ChainableFilter) {
                    continue;
                }

                // If a filter matches, we stop processing this property
                return;
            }
        }

        if ($filterWasApplied) {
            return;
        }

        $property->setAccessible(true);
        $propertyValue = $property->getValue($object);

        // Copy the property
        $property->setValue($object, $this->recursiveCopy($propertyValue));
    }

    private function addFilter(Filter $filter, Matcher $matcher): void
    {
        $this->filters[] = [$matcher, $filter];
    }

    private function addTypeFilter(TypeFilter $filter, TypeMatcher $matcher): void
    {
        $this->typeFilters[] = [$matcher, $filter];
    }

    /**
     * @return TypeFilter|null The first filter that matches variable or `null` if no such filter found
     */
    private function getFirstMatchedTypeFilter($value): ?TypeFilter
    {
        foreach ($this->typeFilters as [$matcher, $typeFilter]) {
            /** @var TypeMatcher $matcher */
            /** @var TypeFilter $typeFilter */
            if ($matcher->matches($value)) {
                return $typeFilter;
            }
        }

        return null;
    }
}
