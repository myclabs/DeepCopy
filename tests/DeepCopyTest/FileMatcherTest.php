<?php

namespace DeepCopyTest;

use DeepCopy\Filter\Filter;
use DeepCopy\FilterMatcher;

/**
 * Test FileMatcher
 */
class FileMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testMatches()
    {
        /** @var Filter $filter */
        $filter = $this->getMockForAbstractClass('DeepCopy\Filter\Filter');

        $fileMatcher = new FilterMatcher('DeepCopyTest\Fixture1', 'property1', $filter);

        $this->assertTrue($fileMatcher->matches(new Fixture1(), 'property1'));
        $this->assertFalse($fileMatcher->matches(new \stdClass(), 'property1'));
        $this->assertFalse($fileMatcher->matches(new Fixture1(), 'property2'));
    }

    public function testGetFilter()
    {
        /** @var Filter $filter */
        $filter = $this->getMockForAbstractClass('DeepCopy\Filter\Filter');

        $fileMatcher = new FilterMatcher('DeepCopyTest\Fixture1', 'property1', $filter);

        $this->assertSame($filter, $fileMatcher->getFilter());
    }
}

class Fixture1 {
    public $property1;
}
