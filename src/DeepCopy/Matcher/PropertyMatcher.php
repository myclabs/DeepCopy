<?php

namespace DeepCopy\Matcher;

/**
 * @final
 */
class PropertyMatcher implements Matcher
{
    private string $class;

    private string $property;

    /**
     * @param string $class    Class name
     * @param string $property Property name
     */
    public function __construct(string $class, string $property)
    {
        $this->class = $class;
        $this->property = $property;
    }

    /**
     * Matches a specific property of a specific class.
     *
     * {@inheritdoc}
     */
    public function matches(object $object, string $property)
    {
        return ($object instanceof $this->class) && $property == $this->property;
    }
}
