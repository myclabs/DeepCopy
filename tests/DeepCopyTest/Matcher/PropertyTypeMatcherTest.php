<?php declare(strict_types=1);

namespace DeepCopyTest\Matcher;

use DeepCopy\f009;
use DeepCopy\Matcher\PropertyTypeMatcher;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \DeepCopy\Matcher\PropertyTypeMatcher
 */
class PropertyTypeMatcherTest extends TestCase
{
    #[DataProvider('providePairs')]
    public function test_it_matches_the_given_property($object, $expected)
    {
        $matcher = new PropertyTypeMatcher(PropertyTypeMatcherTestFixture2::class);

        $actual = $matcher->matches($object, 'foo');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @requires PHP 7.4
     */
    public function test_it_ignores_uninitialized_typed_properties()
    {
        $object = new f009\TypedObjectProperty();

        $matcher = new PropertyTypeMatcher(\DateTime::class);

        $this->assertFalse($matcher->matches($object, 'date'));
    }

    /**
     * @requires PHP 7.4
     */
    public function test_it_matches_initialized_typed_properties()
    {
        $object = new f009\TypedObjectProperty();
        $object->date = new \DateTime();

        $matcher = new PropertyTypeMatcher(\DateTime::class);

        $this->assertTrue($matcher->matches($object, 'date'));
    }

    public static function providePairs(): array
    {
        $object1 = new PropertyTypeMatcherTestFixture1();
        $object1->foo = new PropertyTypeMatcherTestFixture2();

        $object2 = new PropertyTypeMatcherTestFixture1();
        $object2->foo = new stdClass();

        $object3 = new PropertyTypeMatcherTestFixture1();
        $object3->foo = true;

        return [
            [new PropertyTypeMatcherTestFixture1(), false],
            [$object1, true],
            [$object2, false],
            [$object3, false],
            [new stdClass(), false],
        ];
    }
}

class PropertyTypeMatcherTestFixture1
{
    public $foo;
}

class PropertyTypeMatcherTestFixture2
{
}
