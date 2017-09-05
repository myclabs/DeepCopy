<?php

namespace DeepCopy;

/**
 * Deep copy the given value.
 *
 * @param mixed $value
 * @param bool  $useCloneMethod
 *
 * @return mixed
 */
function deep_copy($value, $useCloneMethod = false)
{
    return (new DeepCopy($useCloneMethod))->copy($value);
}
