<?php

namespace DeepCopy;

/**
 * Deep copies the given value.
 *
 * @param mixed $value
 * @param bool  $useCloneMethod
 *
 * @return mixed
 */
function deep_copy($value, $useCloneMethod = false)
{
    static $copier = null;

    if (null === $copier) {
        $copier = new DeepCopy($useCloneMethod);
    }

    return $copier->copy($value);
}
