<?php

namespace DeepCopyTest\Filter;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\ReplaceFilter;
use DeepCopy\Matcher\PropertyMatcher;
use ReflectionProperty;

class ReplaceFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider handlerProvider
     */
    public function testApply(callable $callback, array $expected)
    {
        $object = new ReplaceFilterTestFixture();
        $object->property1 = ['foo' => 'bar', 'baz' => 'foo'];

        $filter = new ReplaceFilter($callback);

        $filter->apply($object, new ReflectionProperty('DeepCopyTest\Filter\ReplaceFilterTestFixture', 'property1'), function () {
            return null;
        });

        $this->assertEquals($expected, $object->property1);
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
        $object = new ReplaceFilterTestFixture();
        $object->property1 = [
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
        }), new PropertyMatcher(get_class($object), 'property1'));

        $copied = $deepCopy->copy($object);

        // Check copied
        $this->assertEquals([
            'foo' => 'bar',
            'baz' => ['bar' => 'dummy_change']
        ], $copied->property1);

        // Check original object is unchanged
        $this->assertEquals(
            ['foo' => 'bar', 'baz' => ['bar' => 'foo'], 'bar' => 'foo'],
            $object->property1
        );
    }
}

class ReplaceFilterTestFixture
{
    public $property1;
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
