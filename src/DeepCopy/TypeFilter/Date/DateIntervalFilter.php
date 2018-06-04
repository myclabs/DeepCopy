<?php declare(strict_types=1);

namespace DeepCopy\TypeFilter\Date;

use DateInterval;
use DeepCopy\TypeFilter\TypeFilter;

final class DateIntervalFilter implements TypeFilter
{
    /**
     * {@inheritdoc}
     *
     * @param DateInterval $value
     *
     * @see https://bugs.php.net/bug.php?id=50559
     */
    public function apply($value)
    {
        $copy = new DateInterval('P0D');

        foreach ($value as $propertyName => $propertyValue) {
            $copy->{$propertyName} = $propertyValue;
        }

        return $copy;
    }
}
