<?php

namespace DeepCopyTest\Filter;

use DeepCopy\Filter\ReplaceFilter;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @covers \DeepCopy\Filter\ReplaceFilter
 */
class ReplaceFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCallbacks
     */
    public function test_it_applies_the_callback_to_the_specified_property(callable $callback, array $expected)
    {
        $object = new stdClass();
        $object->data = [
            'foo' => 'bar',
            'baz' => 'foo'
        ];

        $filter = new ReplaceFilter($callback);

        $filter->apply(
            $object,
            'data',
            function () {
                return null;
            }
            );

        $this->assertEquals($expected, $object->data);
    }

    public function provideCallbacks()
    {
        return [
            [
                function ($data) {
                    unset($data['baz']);

                    return $data;
                },
                ['foo' => 'bar']
            ],
        ];
    }
}
