<?php
namespace DeepCopyTest\Matcher;

use DeepCopy\Matcher\PropertyMatcher;
use ReflectionProperty;

/**
 * Test PropertyMatcher
 */
class PropertyMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testMatches()
    {
        $matcher = new PropertyMatcher('DeepCopyTest\Matcher\PropertyMatcherTestFixture', 'property1');

        $this->assertTrue($matcher->matches(new PropertyMatcherTestFixture(), new ReflectionProperty('DeepCopyTest\Matcher\PropertyMatcherTestFixture', 'property1')));
        $this->assertFalse($matcher->matches(new \stdClass(), new ReflectionProperty('DeepCopyTest\Matcher\PropertyMatcherTestFixture', 'property1')));
        $this->assertFalse($matcher->matches(new PropertyMatcherTestFixture(), new ReflectionProperty('DeepCopyTest\Matcher\PropertyMatcherTestFixture', 'property2')));
    }
}

class PropertyMatcherTestFixture
{
    public $property1;
    public $property2;
}
