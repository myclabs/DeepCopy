<?php

namespace DeepCopy\Matcher;

/**
 * @final
 */
class PropertyNameMatcher implements Matcher
{
    private string $property;

    /**
     * @param string $property Property name
     */
    public function __construct(string $property)
    {
        $this->property = $property;
    }

    /**
     * Matches a property by its name.
     *
     * {@inheritdoc}
     */
    public function matches(object $object, string $property)
    {
        return $property == $this->property;
    }
}
