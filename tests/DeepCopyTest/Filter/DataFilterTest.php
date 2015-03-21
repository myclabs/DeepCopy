<?php

namespace DeepCopyTest\Filter;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\DataFilter;
use DeepCopy\Matcher\PropertyMatcher;

class DataFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider handlerProvider
     */
    public function testApply(Callable $callback, Array $expected)
    {
        $object = new \stdClass();
        $object->data = array('foo' => 'bar', 'baz' => 'foo');

        $filter = new DataFilter($callback);

        $filter->apply($object, 'data', function($item) {
            return null;
        });

        $this->assertEquals($expected, $object->data);
    }

    public function handlerProvider()
    {
        $closure = function($data) {
            unset($data['baz']);
            return $data;
        };

        return array(
            array($closure, array('foo' => 'bar')),
            array('DeepCopyTest\Filter\Callback::copy', array('foo' => 'bar', 'baz' => 'foo', 'foobar' => 'baz')),
            array(array(new Callback(), 'callback'), array('foo' => 'foo'))
        );
    }

    public function testIntegration()
    {
        //Prepare object to copy
        $object = new \StdClass();
        $object->data = array(
            'foo' => 'bar',
            'baz' => array('bar' => 'foo'),
            'bar' => 'foo'
        );

        //Copy
        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(new DataFilter(function($data) {
            $data['baz']['bar'] = 'dummy_change';
            unset($data['bar']);

            return $data;
        }), new PropertyMatcher(get_class($object), 'data'));

        $copied = $deepCopy->copy($object);

        //Check copied
        $this->assertEquals(array(
            'foo' => 'bar',
            'baz' => array('bar' => 'dummy_change')
        ), $copied->data);

        //Check original object is unchanged
        $this->assertEquals(
            array('foo' => 'bar', 'baz' => array('bar' => 'foo'), 'bar' => 'foo'),
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
        return array('foo' => 'foo');
    }
}