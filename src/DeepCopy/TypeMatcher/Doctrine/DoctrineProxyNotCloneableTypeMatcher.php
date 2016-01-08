<?php

namespace DeepCopy\TypeMatcher\Doctrine;

use DeepCopy\TypeMatcher\TypeMatcherInterface;
use Doctrine\Common\Persistence\Proxy;
use ReflectionObject;

class DoctrineProxyNotCloneableTypeMatcher implements TypeMatcherInterface
{
    /**
     * {@inheritdoc}
     */
    public function matches($element)
    {
        if (!is_object($element) || !$element instanceof Proxy) {
            return false;
        }

        $reflectionObject = new ReflectionObject($element);

        if ($isCloneable = $reflectionObject->getParentClass()->isCloneable()) {
            $element->__load();
        }

        return !$isCloneable;
    }
}
