<?php

namespace DeepCopy\Filter;

class DataFilter implements Filter
{
    /**
     * @var Callable
     */
    protected $callback;

    /**
     * @param Callable $callable
     */
    public function __construct(Callable $callable)
    {
        $this->callback = $callable;
    }

    /**
     * Apply the filter to the object.
     *
     * @param object   $object
     * @param string   $property
     * @param Callable $objectCopier
     */
    public function apply($object, $property, $objectCopier)
    {
        $reflectionProperty = new \ReflectionProperty($object, $property);
        $reflectionProperty->setAccessible(true);

        if ($this->callback instanceof \Closure) { //Avoid call_user_func if we can
            $value = $this->callback->__invoke($reflectionProperty->getValue($object));
        } else {
            $value = call_user_func($this->callback, $reflectionProperty->getValue($object));
        }

        $reflectionProperty->setValue($object, $value);
    }
}