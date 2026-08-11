<?php

namespace DeepCopy\Matcher\Doctrine;

use DeepCopy\Matcher\Matcher;
use Doctrine\Persistence\Proxy;

/**
 * @final
 */
class DoctrineProxyMatcher implements Matcher
{
    /**
     * Matches a Doctrine Proxy class.
     *
     * {@inheritdoc}
     */
    public function matches(object $object, string $property)
    {
        return $object instanceof Proxy;
    }
}
