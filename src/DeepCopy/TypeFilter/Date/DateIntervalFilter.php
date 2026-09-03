<?php

namespace DeepCopy\TypeFilter\Date;

use DateInterval;
use DeepCopy\TypeFilter\TypeFilter;

/**
 * @final
 *
 * @deprecated Will be removed in 2.0. This filter will no longer be necessary in PHP 7.1+.
 */
class DateIntervalFilter implements TypeFilter
{

    /**
     * {@inheritdoc}
     *
     * @param DateInterval $element
     *
     * @see http://news.php.net/php.bugs/205076
     */
    public function apply(mixed $element)
    {
        $properties = get_object_vars($element);

        // Since PHP 8.2 an interval from createFromDateString() exposes
        // "from_string"/"date_string" instead of y/m/d/h/i/s, and "from_string" is
        // ignored on assignment, so copying property by property would zero it.
        if (!empty($properties['from_string'])) {
            return DateInterval::createFromDateString($properties['date_string']);
        }

        $copy = new DateInterval('P0D');

        foreach ($properties as $propertyName => $propertyValue) {
            $copy->{$propertyName} = $propertyValue;
        }

        return $copy;
    }
}
