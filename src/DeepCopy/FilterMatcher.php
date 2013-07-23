<?php

namespace DeepCopy;

use DeepCopy\Filter\Filter;

/**
 * Property matcher
 */
class FilterMatcher
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $property;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @param string $class    Class name
     * @param string $property Property name
     * @param Filter $filter
     */
    public function __construct($class, $property, Filter $filter)
    {
        $this->class = $class;
        $this->property = $property;
        $this->filter = $filter;
    }

    /**
     * @param object $object
     * @param string $property
     * @return boolean
     */
    public function matches($object, $property)
    {
        return ($object instanceof $this->class) && ($property == $this->property);
    }

    /**
     * @return Filter
     */
    public function getFilter()
    {
        return $this->filter;
    }
}
