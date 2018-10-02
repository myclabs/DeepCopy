# DeepCopy

DeepCopy helps you create deep copies (clones) of your objects. It is designed to handle cycles in the association graph.

[![Build Status](https://travis-ci.org/myclabs/DeepCopy.svg?branch=2.x)](https://travis-ci.org/myclabs/DeepCopy)
[![Coverage Status](https://coveralls.io/repos/myclabs/DeepCopy/badge.png?branch=2.x)](https://coveralls.io/r/myclabs/DeepCopy?branch=2.x)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/myclabs/DeepCopy/badges/quality-score.png?s=2747100c19b275f93a777e3297c6c12d1b68b934)](https://scrutinizer-ci.com/g/myclabs/DeepCopy/)
[![Total Downloads](https://poser.pugx.org/myclabs/deep-copy/downloads.svg)](https://packagist.org/packages/myclabs/deep-copy)


## Table of Contents

1. [Installation](#installation)
1. [Why](#why)
    1. [Using simply `clone`](#using-simply-clone)
    1. [Overridding `__clone()`](#overridding-__clone)
    1. [With `DeepCopy`](#with-deepcopy)
1. [Usage](#usage)
1. [Going further](#going-further)
    1. [Matchers](#matchers)
        1. [Property name](#property-name)
        1. [Specific property](#specific-property)
        1. [Type](#type)
    1. [Filters](#filters)
        1. [`SetNullFilter`](#setnullfilter-filter)
        1. [`KeepFilter`](#keepfilter-filter)
        1. [`DoctrineCollectionFilter`](#doctrinecollectionfilter-filter)
        1. [`DoctrineEmptyCollectionFilter`](#doctrineemptycollectionfilter-filter)
        1. [`DoctrineProxyFilter`](#doctrineproxyfilter-filter)
        1. [`ReplaceFilter`](#replacefilter-type-filter)
        1. [`ShallowCopyFilter`](#shallowcopyfilter-type-filter)
1. [Contributing](#contributing)
    1. [Tests](#tests)


## Installation

With [Composer][composer]:

```json
composer require myclabs/deep-copy
```


## Why?

- How do you create copies of your objects?

```php
$myCopy = clone $myObject;
```

- How do you create **deep** copies of your objects (i.e. copying also all the objects referenced in the properties)?

You use [`__clone()`][clone] and implement the behavior yourself.

- But how do you handle **cycles** in the association graph?

Now you're in for a big mess :(

![association graph](doc/graph.png)


### Using simply `clone`

![Using clone](doc/clone.png)


### Overridding `__clone()`

![Overridding __clone](doc/deep-clone.png)


### With `DeepCopy`

![With DeepCopy](doc/deep-copy.png)


## Usage

DeepCopy recursively traverses all the object's properties and clones them. To avoid cloning the same object twice it
keeps a hash map of all instances and thus preserves the object graph.

To use it:

```php
use function DeepCopy\deep_copy;

$copy = deep_copy($var);
```

Alternatively, you can create your own `DeepCopy` instance to configure it differently for example:

```php
use DeepCopy\DeepCopy;

$copier = new DeepCopy(true);

$copy = $copier->copy($var);
```

Or you may want to roll your own deep copy function:

```php
namespace Acme;

use DeepCopy\DeepCopy;

function deep_copy($var)
{
    static $copier = null;
    
    if (null === $copier) {
        $copier = new DeepCopy(true);
    }
    
    return $copier->copy($var);
}
```


## Going further

You can add filters to customize the copy process by adding filters:

```php
$copier = new DeepCopy();
$copier->addFilter($filter, $matcher);
```

During the copy process, when a property is matched by a [matcher][matcher], then the [filter][filter] associated to
this matcher is applied. By default, when a filter is applied this stops the process, i.e. the next matcher-filter pair
will not be checked, unless the filter is implemented as a [chainable filter][chainable filter].

Some generic filters and matchers are already provided.


### Matchers

- [`Matcher`][matcher] applies on a object attribute
- [`TypeMatcher`][type matcher] applies on any element found in graph, including
  array elements


#### Property name

The [`PropertyNameMatcher`][property name matcher] will match a property by its name:

```php
use DeepCopy\Matcher\PropertyNameMatcher;

// Will apply a filter to any property of any objects named "id"
$matcher = new PropertyNameMatcher('id');
```


#### Specific property

The [`PropertyMatcher`][property matcher] will match a specific property of a specific class:

```php
use DeepCopy\Matcher\PropertyMatcher;

// Will apply a filter to the property "id" of any instances of the class "MyClass"
$matcher = new PropertyMatcher('MyClass', 'id');
```


#### Type

The [`TypeMatcher`][type matcher] will match any element by its type (instance of a class or any value that could be
parameter of [`gettype()`][gettype] function):

```php
use DeepCopy\TypeMatcher\TypeMatcher;
use Doctrine\Common\Collections\Collection;

// Will apply a filter to any object that is an instance of Doctrine\Common\Collections\Collection
$matcher = new TypeMatcher(Collection::class);
```


### Filters

- [`Filter`][filter] applies a transformation to the object attribute matched by [`Matcher`][matcher]
- [`TypeFilter`][type filter] applies a transformation to any element matched by [`TypeMatcher`][type matcher]

Except a few exceptions (when the filter is a [chainable filter][chainable filter] like
[`DoctrineProxyFilter`](#doctrineproxyfilter-filter)), matching a filter will stop the chain of filters (i.e. the next
ones will not be applied).


#### `SetNullFilter` (filter)

Let's say for example that you are copying a database record (or a Doctrine entity), so you want the copy not to have
any ID:

```php
use DeepCopy\DeepCopy;
use DeepCopy\Matcher\PropertyNameMatcher;
use DeepCopy\Filter\SetNullFilter;

$object = MyClass::load(123);
echo $object->id; // 123

$copier = new DeepCopy();
$copier->addFilter(new SetNullFilter(), new PropertyNameMatcher('id'));

$copy = $copier->copy($object);

echo $copy->id; // null
```


#### `KeepFilter` (filter)

If you want a property to remain untouched (for example, an association to an object):

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\KeepFilter;
use DeepCopy\Matcher\PropertyMatcher;

$copier = new DeepCopy();
$copier->addFilter(
    new KeepFilter(),
    new PropertyMatcher(MyClass::class, 'category')
);

$copy = $copier->copy($object); // $object is an instance of MyClass
// $copy->category has not been touched
```


#### `DoctrineCollectionFilter` (filter)

This filters allows to copy a Doctrine entity:

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineCollectionFilter;
use DeepCopy\Matcher\PropertyTypeMatcher;
use Doctrine\Common\Collections\Collection;

$copier = new DeepCopy();
$copier->addFilter(
    new DoctrineCollectionFilter(),
    new PropertyTypeMatcher(Collection::class)
);

$copy = $copier->copy($object);
```


#### `DoctrineEmptyCollectionFilter` (filter)

If you use Doctrine and want to copy an entity who contains a `Collection` that you want to be reset, you can use this
filter:

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineEmptyCollectionFilter;
use DeepCopy\Matcher\PropertyMatcher;

$copier = new DeepCopy();
$copier->addFilter(
    new DoctrineEmptyCollectionFilter(),
    new PropertyMatcher(MyClass::class, 'myProperty')
);

$copy = $copier->copy($object);

// $copy->myProperty will return an empty collection
```


#### `DoctrineProxyFilter` (filter)

If you use Doctrine and use cloning on lazy loaded entities, you might encounter errors mentioning missing fields on a
Doctrine proxy class (...\\\_\_CG\_\_\Proxy).
You can use this filter to load the actual entity behind the Doctrine proxy class.

**Make sure, though, to put this as one of your very first filters in the filter chain so that the entity is loaded
before other filters are applied!**

This filter won't stop the chain of filters (i.e. the next ones may be applied).

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineProxyFilter;
use DeepCopy\Filter\SetNullFilter;
use DeepCopy\Matcher\Doctrine\DoctrineProxyMatcher;
use DeepCopy\Matcher\PropertyNameMatcher;

$copier = new DeepCopy();
$copier->addFilter(new DoctrineProxyFilter(), new DoctrineProxyMatcher());
$copier->addFilter(new SetNullFilter(), new PropertyNameMatcher('id'));

$copy = $copier->copy($object);

// $copy should now contain a clone of all entities, including those that were not yet fully loaded.
```


#### `ReplaceFilter` (type filter)

1. If you want to replace the value of a property:

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\ReplaceFilter;
use DeepCopy\Matcher\PropertyMatcher;

$copier = new DeepCopy();
$copier->addFilter(
    new ReplaceFilter(
        function ($currentValue): string {
            return $currentValue . ' (copy)'
        }
    ),
    new PropertyMatcher(MyClass::class, 'title')
);

$copy = $copier->copy($object); // $object is an instance of MyClass

// $copy->title will contain the data returned by the callback, e.g. 'The title (copy)'
```

2. If you want to replace whole element:

```php
use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\ReplaceFilter;
use DeepCopy\TypeMatcher\TypeMatcher;

$copier = new DeepCopy();
$copier->addFilter(
    new ReplaceFilter(
        function (MyClass $myClass): string {
            return get_class($myClass)
        }
    ),
    new TypeMatcher(MyClass::class)
);

$copy = $copier->copy([new MyClass, 'some string', new MyClass]);

// $copy will contain ['MyClass', 'some string', 'MyClass']
```


#### `ShallowCopyFilter` (type filter)

Stop *DeepCopy* from recursively copying element, using standard [`clone`][clone] instead:

```php
use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\ShallowCopyFilter;
use DeepCopy\TypeMatcher\TypeMatcher;
use Mockery as m;

$copier = new DeepCopy();
$copier->addTypeFilter(
	new ShallowCopyFilter,
	new TypeMatcher(m\MockInterface::class)
);

$myServiceWithMocks = new MyService(
    m::mock(MyDependency1::class),
    m::mock(MyDependency2::class)
);

$copy = $copier->copy($myServiceWithMocks)
// All mocks will be just cloned, not deep copied
```


## Contributing

This package is distributed under the MIT license.


### Tests

Running the tests is simple:

```php
vendor/bin/phpunit
```


[chainable filter]: src/DeepCopy/Filter/ChainableFilter.php
[clone]: https://secure.php.net/manual/en/language.oop5.cloning.php#object.clone
[composer]: https://getcomposer.org/
[gettype]: https://secure.php.net/manual/en/function.gettype.php
[filter]: src/DeepCopy/Filter/Filter.php
[matcher]: src/DeepCopy/Matcher/Matcher.php
[property matcher]: src/DeepCopy/Matcher/PropertyMatcher.php
[property name matcher]: src/DeepCopy/Matcher/PropertyNameMatcher.php
[type matcher]: src/DeepCopy/TypeMatcher/TypeMatcher.php
[type filter]: src/DeepCopy/TypeFilter/TypeFilter.php
