<?php

namespace DeepCopy\Matcher\Doctrine;

use DeepCopy\Matcher\Matcher;
use Doctrine\Common\Persistence\Proxy;

/**
 * Match a Doctrine Proxy class.
 *
 * @final
 */
class DoctrineProxyMatcher implements Matcher
{
    /**
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return $object instanceof Proxy;
    }
}
