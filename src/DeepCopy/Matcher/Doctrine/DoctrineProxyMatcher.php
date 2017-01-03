<?php

namespace DeepCopy\Matcher\Doctrine;

use DeepCopy\Matcher\Matcher;
use Doctrine\Common\Persistence\Proxy;

/**
 * Match a specific property of a specific class
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
