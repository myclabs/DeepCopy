<?php

namespace DeepCopy\Matcher\Doctrine;

use DeepCopy\Matcher\Matcher;

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
    public function matches($object, $property)
    {
        if (class_exists('\Doctrine\Persistence\Proxy')) {
            return $object instanceof \Doctrine\Persistence\Proxy;
        }

        return $object instanceof \Doctrine\Common\Persistence\Proxy;
    }

}
