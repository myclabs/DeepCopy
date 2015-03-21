<?php

namespace DeepCopyTest\Filter;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\ReplaceFilter;
use DeepCopy\Matcher\PropertyMatcher;

class ReplaceFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider handlerProvider
     */
    public function testApply(callable $callback, array $expected)
    {
        $object = new \stdClass();
        $object->data = ['foo' => 'bar', 'baz' => 'foo'];

        $filter = new ReplaceFilter($callback);

        $filter->apply($object, 'data', function () {
            return null;
        });

        $this->assertEquals($expected, $object->data);
    }

    public function handlerProvider()
    {
        $closure = function ($data) {
            unset($data['baz']);
            return $data;
        };

        return [
            [$closure, ['foo' => 'bar']],
            ['DeepCopyTest\Filter\Callback::copy', ['foo' => 'bar', 'baz' => 'foo', 'foobar' => 'baz']],
            [[new Callback(), 'callback'], ['foo' => 'foo']]
        ];
    }

    public function testIntegration()
    {
        // Prepare object to copy
        $object = new \StdClass();
        $object->data = [
            'foo' => 'bar',
            'baz' => ['bar' => 'foo'],
            'bar' => 'foo'
        ];

        // Copy
        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(new ReplaceFilter(function ($data) {
            $data['baz']['bar'] = 'dummy_change';
            unset($data['bar']);

            return $data;
        }), new PropertyMatcher(get_class($object), 'data'));

        $copied = $deepCopy->copy($object);

        // Check copied
        $this->assertEquals([
            'foo' => 'bar',
            'baz' => ['bar' => 'dummy_change']
        ], $copied->data);

        // Check original object is unchanged
        $this->assertEquals(
            ['foo' => 'bar', 'baz' => ['bar' => 'foo'], 'bar' => 'foo'],
            $object->data
        );
    }
}

class Callback
{
    public static function copy($data)
    {
        $data['foobar'] = 'baz';
        return $data;
    }

    public function callback($data)
    {
        return ['foo' => 'foo'];
    }
}
