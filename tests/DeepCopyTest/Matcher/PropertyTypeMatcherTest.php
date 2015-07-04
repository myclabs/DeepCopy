<?php
namespace DeepCopyTest\Matcher;

use DeepCopy\Matcher\PropertyTypeMatcher;

/**
 * Test PropertyNameMatcher
 */
class PropertyTypeMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testMatches()
    {
        $matcher = new PropertyTypeMatcher('DeepCopyTest\Matcher\PropertyTypeMatcherTestFixture2');

        $o = new PropertyTypeMatcherTestFixture1();
        $this->assertFalse($matcher->matches($o, 'property1'));

        $o->property1 = new PropertyTypeMatcherTestFixture1();
        $this->assertFalse($matcher->matches($o, 'property1'));

        $o->property1 = new PropertyTypeMatcherTestFixture2();
        $this->assertTrue($matcher->matches($o, 'property1'));
    }
}

class PropertyTypeMatcherTestFixture1
{
    public $property1;
}

class PropertyTypeMatcherTestFixture2
{
}
