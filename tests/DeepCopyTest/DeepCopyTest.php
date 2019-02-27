<?php

namespace DeepCopyTest;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use DeepCopy\DeepCopy;
use DeepCopy\Exception\CloneException;
use DeepCopy\f001;
use DeepCopy\f002;
use DeepCopy\f003;
use DeepCopy\f004;
use DeepCopy\f005;
use DeepCopy\f006;
use DeepCopy\f007;
use DeepCopy\f008;
use DeepCopy\Filter\KeepFilter;
use DeepCopy\Filter\SetNullFilter;
use DeepCopy\Matcher\PropertyNameMatcher;
use DeepCopy\Matcher\PropertyTypeMatcher;
use DeepCopy\TypeFilter\ShallowCopyFilter;
use DeepCopy\TypeMatcher\TypeMatcher;
use PHPUnit\Framework\TestCase;
use SplDoublyLinkedList;
use stdClass;
use function DeepCopy\deep_copy;

/**
 * @covers \DeepCopy\DeepCopy
 */
class DeepCopyTest extends TestCase
{
    /**
     * @dataProvider provideScalarValues
     */
    public function test_it_can_copy_scalar_values($value)
    {
        $copy = deep_copy($value);

        $this->assertSame($value, $copy);
    }

    public function provideScalarValues()
    {
        return [
            [true],
            ['string'],
            [null],
            [10],
            [-1],
            [.5],
        ];
    }

    public function test_it_can_copy_an_array_of_scalar_values()
    {
        $copy = deep_copy([10, 20]);

        $this->assertSame([10, 20], $copy);
    }

    public function test_it_can_copy_an_object()
    {
        $object = new stdClass();

        $copy = deep_copy($object);

        $this->assertEqualButNotSame($object, $copy);
    }

    public function test_it_can_copy_an_array_of_objects()
    {
        $object = [new stdClass()];

        $copy = deep_copy($object);

        $this->assertEqualButNotSame($object, $copy);
        $this->assertEqualButNotSame($object[0], $copy[0]);
    }

    /**
     * @dataProvider provideObjectWithScalarValues
     */
    public function test_it_can_copy_an_object_with_scalar_properties($object, $expectedVal)
    {
        $copy = deep_copy($object);

        $this->assertEqualButNotSame($object, $copy);
        $this->assertSame($expectedVal, $copy->prop);
    }

    public function provideObjectWithScalarValues()
    {
        $createObject = function ($val) {
            $object = new stdClass();

            $object->prop = $val;

            return $object;
        };

        return array_map(
            function (array $vals) use ($createObject) {
                return [$createObject($vals[0]), $vals[0]];
            },
            $this->provideScalarValues()
        );
    }

    public function test_it_can_copy_an_object_with_an_object_property()
    {
        $foo = new stdClass();
        $bar = new stdClass();

        $foo->bar = $bar;

        $copy = deep_copy($foo);

        $this->assertEqualButNotSame($foo, $copy);
        $this->assertEqualButNotSame($foo->bar, $copy->bar);
    }

    public function test_it_copies_dynamic_properties()
    {
        $foo = new stdClass();
        $bar = new stdClass();

        $foo->bar = $bar;

        $copy = deep_copy($foo);

        $this->assertEqualButNotSame($foo, $copy);
        $this->assertEqualButNotSame($foo->bar, $copy->bar);
    }

    /**
     * @ticket https://github.com/myclabs/DeepCopy/issues/38
     * @ticket https://github.com/myclabs/DeepCopy/pull/70
     * @ticket https://github.com/myclabs/DeepCopy/pull/76
     */
    public function test_it_can_copy_an_object_with_a_date_object_property()
    {
        $object = new stdClass();

        $object->d1 = new DateTime();
        $object->d2 = new DateTimeImmutable();
        $object->dtz = new DateTimeZone('UTC');
        $object->di = new DateInterval('P2D');

        $copy = deep_copy($object);

        $this->assertEqualButNotSame($object->d1, $copy->d1);
        $this->assertEqualButNotSame($object->d2, $copy->d2);
        $this->assertEqualButNotSame($object->dtz, $copy->dtz);
        $this->assertEqualButNotSame($object->di, $copy->di);
    }

    /**
     * @ticket https://github.com/myclabs/DeepCopy/pull/70
     */
    public function test_it_skips_the_copy_for_userland_datetimezone()
    {
        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(
            new SetNullFilter(),
            new PropertyNameMatcher('cloned')
        );

        $object = new stdClass();

        $object->dtz = new f007\FooDateTimeZone('UTC');

        $copy = $deepCopy->copy($object);

        $this->assertTrue($copy->dtz->cloned);
    }

    /**
     * @ticket https://github.com/myclabs/DeepCopy/pull/76
     */
    public function test_it_skips_the_copy_for_userland_dateinterval()
    {
        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(
            new SetNullFilter(),
            new PropertyNameMatcher('cloned')
        );

        $object = new stdClass();

        $object->di = new f007\FooDateInterval('P2D');

        $copy = $deepCopy->copy($object);

        $this->assertFalse($copy->di->cloned);
    }

    public function test_it_copies_the_private_properties_of_the_parent_class()
    {
        $object = new f001\B();

        $object->setAProp($aStdClass = new stdClass());
        $object->setBProp($bStdClass = new stdClass());

        /** @var f001\B $copy */
        $copy = deep_copy($object);

        $this->assertEqualButNotSame($aStdClass, $copy->getAProp());
        $this->assertEqualButNotSame($bStdClass, $copy->getBProp());
    }

    public function test_it_keeps_reference_of_the_copied_objects_when_copying_the_graph()
    {
        $a = new f002\A();

        $b = new stdClass();
        $c = new stdClass();

        $a->setProp1($b);
        $a->setProp2($c);

        $b->c = $c;

        /** @var f002\A $copy */
        $copy = deep_copy($a);

        $this->assertEqualButNotSame($a, $copy);
        $this->assertEqualButNotSame($b, $copy->getProp1());
        $this->assertEqualButNotSame($c, $copy->getProp2());

        $this->assertSame($copy->getProp1()->c, $copy->getProp2());
    }

    public function test_it_can_copy_graphs_with_circular_references()
    {
        $a = new stdClass();
        $b = new stdClass();

        $a->prop = $b;
        $b->prop = $a;

        $copy = deep_copy($a);

        $this->assertEqualButNotSame($a, $copy);
        $this->assertEqualButNotSame($b, $copy->prop);

        $this->assertSame($copy, $copy->prop->prop);
    }

    public function test_it_can_copy_graphs_with_circular_references_with_userland_class()
    {
        $a = new f003\Foo('a');
        $b = new f003\Foo('b');

        $a->setProp($b);
        $b->setProp($a);

        /** @var f003\Foo $copy */
        $copy = deep_copy($a);

        $this->assertEqualButNotSame($a, $copy);
        $this->assertEqualButNotSame($b, $copy->getProp());

        $this->assertSame($copy, $copy->getProp()->getProp());
    }

    public function test_it_cannot_copy_unclonable_items()
    {
        $object = new f004\UnclonableItem();

        try {
            deep_copy($object);

            $this->fail('Expected exception to be thrown.');
        } catch (CloneException $exception) {
            $this->assertSame(
                sprintf(
                    'The class "%s" is not cloneable.',
                    f004\UnclonableItem::class
                ),
                $exception->getMessage()
            );
            $this->assertSame(0, $exception->getCode());
            $this->assertNull($exception->getPrevious());
        }
    }

    public function test_it_can_skip_uncloneable_objects()
    {
        $object = new f004\UnclonableItem();

        $deepCopy = new DeepCopy();
        $deepCopy->skipUncloneable(true);

        $copy = $deepCopy->copy($object);

        $this->assertSame($object, $copy);
    }

    public function test_it_uses_the_userland_defined_cloned_method()
    {
        $object = new f005\Foo();

        $copy = deep_copy($object);

        $this->assertTrue($copy->cloned);
    }

    public function test_it_only_uses_the_userland_defined_cloned_method_when_configured_to_do_so()
    {
        $object = new f005\Foo();
        $object->foo = new stdClass();

        $copy = deep_copy($object, true);

        $this->assertTrue($copy->cloned);
        $this->assertSame($object->foo, $copy->foo);
    }

    public function test_it_uses_type_filter_to_copy_objects_if_matcher_matches()
    {
        $deepCopy = new DeepCopy();
        $deepCopy->addTypeFilter(
            new ShallowCopyFilter(),
            new TypeMatcher(f006\A::class)
        );

        $a = new f006\A;
        $b = new f006\B;

        $a->setAProp($b);

        /** @var f006\A $copy */
        $copy = $deepCopy->copy($a);

        $this->assertTrue($copy->cloned);
        $this->assertFalse($copy->getAProp()->cloned);
        $this->assertSame($b, $copy->getAProp());
    }

    public function test_it_uses_filters_to_copy_object_properties_if_matcher_matches()
    {
        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(
            new SetNullFilter(),
            new PropertyNameMatcher('cloned')
        );

        $a = new f006\A;
        $b = new f006\B;

        $a->setAProp($b);

        /** @var f006\A $copy */
        $copy = $deepCopy->copy($a);

        $this->assertNull($copy->cloned);
        $this->assertNull($copy->getAProp()->cloned);
    }

    public function test_it_uses_the_first_filter_matching_for_copying_object_properties()
    {
        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(
            new SetNullFilter(),
            new PropertyNameMatcher('cloned')
        );
        $deepCopy->addFilter(
            new KeepFilter(),
            new PropertyNameMatcher('cloned')
        );

        $a = new f006\A;
        $b = new f006\B;

        $a->setAProp($b);

        /** @var f006\A $copy */
        $copy = $deepCopy->copy($a);

        $this->assertNull($copy->cloned);
        $this->assertNull($copy->getAProp()->cloned);
    }

    /**
     * @ticket https://github.com/myclabs/DeepCopy/pull/49
     */
    public function test_it_can_copy_a_SplDoublyLinkedList()
    {
        $object = new SplDoublyLinkedList();

        $a = new stdClass();
        $b = new stdClass();

        $a->b = $b;

        $object->push($a);

        /** @var SplDoublyLinkedList $copy */
        $copy = deep_copy($object);

        $this->assertEqualButNotSame($object, $copy);

        $aCopy = $copy->pop();

        $this->assertEqualButNotSame($b, $aCopy->b);
    }

    /**
     * @ticket https://github.com/myclabs/DeepCopy/issues/62
     */
    public function test_matchers_can_access_to_parent_private_properties()
    {
        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(new SetNullFilter(), new PropertyTypeMatcher(stdClass::class));

        $object = new f008\B(new stdClass());

        /** @var f008\B $copy */
        $copy = $deepCopy->copy($object);

        $this->assertNull($copy->getFoo());
    }

    public function test_it_can_prepend_filter()
    {
        $object = new f008\A('bar');
        $deepCopy = new DeepCopy();
        $deepCopy->addFilter(new KeepFilter(), new PropertyNameMatcher('foo'));
        $deepCopy->prependFilter(new SetNullFilter(), new PropertyNameMatcher('foo'));
        $copy = $deepCopy->copy($object);
        $this->assertNull($copy->getFoo());
    }

    private function assertEqualButNotSame($expected, $val)
    {
        $this->assertEquals($expected, $val);
        $this->assertNotSame($expected, $val);
    }
}
