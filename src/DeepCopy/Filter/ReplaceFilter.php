<?php

namespace DeepCopy\Filter;

use DeepCopy\Reflection\ReflectionHelper;

/**
 * Replace the value of a property
 *
 * @final
 */
class ReplaceFilter implements Filter
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callable Will be called to get the new value for each property to replace
     */
    public function __construct(callable $callable)
    {
        $this->callback = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function apply($object, $property, $objectCopier)
    {
        $reflectionProperty =  ReflectionHelper::getProperty($object, $property);
        $reflectionProperty->setAccessible(true);

        $value = call_user_func($this->callback, $reflectionProperty->getValue($object));

        $reflectionProperty->setValue($object, $value);
    }
}
