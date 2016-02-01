<?php
namespace DeepCopyTest\Matcher;

use DeepCopy\Matcher\PropertyNameMatcher;
use ReflectionProperty;

/**
 * Test PropertyNameMatcher
 */
class PropertyNameMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testMatches()
    {
        $matcher = new PropertyNameMatcher('property1');

        $this->assertTrue($matcher->matches(new \stdClass(), new ReflectionProperty('DeepCopyTest\Matcher\PropertyNameMatcherTestFixture', 'property1')));
        $this->assertFalse($matcher->matches(new \stdClass(), new ReflectionProperty('DeepCopyTest\Matcher\PropertyNameMatcherTestFixture', 'property2')));
    }
}

class PropertyNameMatcherTestFixture
{
    public $property1;
    public $property2;
}
