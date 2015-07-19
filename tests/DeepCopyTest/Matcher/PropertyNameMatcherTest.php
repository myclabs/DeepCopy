<?php
namespace DeepCopyTest\Matcher;

use DeepCopy\Matcher\PropertyNameMatcher;

/**
 * Test PropertyNameMatcher
 */
class PropertyNameMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testMatches()
    {
        $matcher = new PropertyNameMatcher('property1');

        $this->assertTrue($matcher->matches(new \stdClass(), 'property1'));
        $this->assertFalse($matcher->matches(new \stdClass(), 'property2'));
    }
}
