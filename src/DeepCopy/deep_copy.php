<?php declare(strict_types=1);

namespace DeepCopy;

/**
 * Deep copies the given value.
 *
 * @param mixed $value
 * @param bool $useCloneMethod           If set to true, when an object implements the __clone() function, it will
 *                                       be used instead of the regular deep cloning.
 * @param bool $skipUncloneable          If enabled, will not throw an exception when coming across an uncloneable
 *                                       property.
 * @param Array<Filter, Matcher>         List of filter-matcher pairs
 * @param Array<TypeFilter, TypeMatcher> List of type filter-matcher pairs
 */
function deep_copy(
    $value,
    bool $useCloneMethod = false,
    bool $skipUncloneable = false,
    array $filters = [],
    array $typeFilters = []
) {
    return (new DeepCopy($useCloneMethod, $skipUncloneable, $filters, $typeFilters))->copy($value);
}
