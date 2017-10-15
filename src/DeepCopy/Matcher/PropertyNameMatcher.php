<?php

namespace DeepCopy\Matcher;

/**
 * Matches a property by its name.
 *
 * @final
 */
class PropertyNameMatcher implements Matcher
{
    /**
     * @var string
     */
    private $property;

    /**
     * @param string $property Property name
     */
    public function __construct($property)
    {
        $this->property = $property;
    }

    /**
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return $property == $this->property;
    }
}
