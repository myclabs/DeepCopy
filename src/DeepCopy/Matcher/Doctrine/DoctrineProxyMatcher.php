<?php

namespace DeepCopy\Matcher\Doctrine;

use DeepCopy\Matcher\Matcher;
use Doctrine\Common\Persistence\Proxy;
use ReflectionProperty;

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
    public function matches($object, ReflectionProperty $reflectionProperty)
    {
        return $object instanceof Proxy;
    }
}
