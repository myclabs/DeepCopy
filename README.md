# DeepCopy

DeepCopy helps you create deep copies (clones) of your objects. It is designed to handle cycles in the association graph.

[![Build Status](https://travis-ci.org/myclabs/DeepCopy.png?branch=master)](https://travis-ci.org/myclabs/DeepCopy) [![Coverage Status](https://coveralls.io/repos/myclabs/DeepCopy/badge.png?branch=master)](https://coveralls.io/r/myclabs/DeepCopy?branch=master) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/myclabs/DeepCopy/badges/quality-score.png?s=2747100c19b275f93a777e3297c6c12d1b68b934)](https://scrutinizer-ci.com/g/myclabs/DeepCopy/)
[![Total Downloads](https://poser.pugx.org/myclabs/deep-copy/downloads.svg)](https://packagist.org/packages/myclabs/deep-copy)


## Table of Contents

1. [How](#how)
1. [Why](#why)
    1. [Using simply `clone`](#using-simply-clone)
    1. [Overridding `__clone()`](#overridding-__clone)
    1. [With `DeepCopy`](#with-deepcopy)
1. [How it works](#how-it-works)
1. [Going further](#going-further)
    1. [Matchers](#matchers)
        1. [Property name](#property-name)
        1. [Specific property](#specific-property)
        1. [Type](#type)
    1. [Filters](#filters)
        1. [`SetNullFilter`](#setnullfilter)
        1. [`KeepFilter`](#keepfilter)
        1. [`ReplaceFilter`](#replacefilter)
        1. [`ShallowCopyFilter`](#doctrinecollectionfilter)
        1. [`DoctrineCollectionFilter`](#doctrinecollectionfilter)
        1. [`DoctrineEmptyCollectionFilter`](#doctrineemptycollectionfilter)
1. [Contributing](#contributing)
    1. [Tests](#tests)

## How?

Install with Composer:

```json
composer require myclabs/deep-copy
```

Use simply:

```php
use DeepCopy\DeepCopy;

$deepCopy = new DeepCopy();
$myCopy = $deepCopy->copy($myObject);
```


## Why?

- How do you create copies of your objects?

```php
$myCopy = clone $myObject;
```

- How do you create **deep** copies of your objects (i.e. copying also all the objects referenced in the properties)?

You use [`__clone()`](http://www.php.net/manual/en/language.oop5.cloning.php#object.clone) and implement the behavior yourself.

- But how do you handle **cycles** in the association graph?

Now you're in for a big mess :(

![association graph](doc/graph.png)

### Using simply `clone`

![Using clone](doc/clone.png)

### Overridding `__clone()`

![Overridding __clone](doc/deep-clone.png)

### With `DeepCopy`

![With DeepCopy](doc/deep-copy.png)

## How it works

DeepCopy recursively traverses all the object's properties and clones them. To avoid cloning the same object twice it keeps a hash map of all instances and thus preserves the object graph.

## Going further

You can add filters to customize the copy process.

The method to add a filter is `$deepCopy->addFilter($filter, $matcher)`,
with `$filter` implementing `DeepCopy\Filter\Filter`
and `$matcher` implementing `DeepCopy\Matcher\Matcher`.

We provide some generic filters and matchers.

### Matchers

  - `DeepCopy\Matcher` applies on a object attribute.
  - `DeepCopy\TypeMatcher` applies on any element found in graph, including array elements.

#### Property name

The `PropertyNameMatcher` will match a property by its name:

```php
use DeepCopy\Matcher\PropertyNameMatcher;

$matcher = new PropertyNameMatcher('id');
// will apply a filter to any property of any objects named "id"
```

#### Specific property

The `PropertyMatcher` will match a specific property of a specific class:

```php
use DeepCopy\Matcher\PropertyMatcher;

$matcher = new PropertyMatcher('MyClass', 'id');
// will apply a filter to the property "id" of any objects of the class "MyClass"
```

#### Type

The `TypeMatcher` will match any element by its type (instance of a class or any value that could be parameter of [gettype()](http://php.net/manual/en/function.gettype.php) function):

```php
use DeepCopy\TypeMatcher\TypeMatcher;

$matcher = new TypeMatcher('Doctrine\Common\Collections\Collection');
// will apply a filter to any object that is an instance of Doctrine\Common\Collections\Collection
```

### Filters

  - `DeepCopy\Filter` applies a transformation to the object attribute matched by `DeepCopy\Matcher`.
  - `DeepCopy\TypeFilter` applies a transformation to any element matched by `DeepCopy\TypeMatcher`.

#### `SetNullFilter`

Let's say for example that you are copying a database record (or a Doctrine entity), so you want the copy not to have any ID:

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\SetNullFilter;
use DeepCopy\Matcher\PropertyNameMatcher;

$myObject = MyClass::load(123);
echo $myObject->id; // 123

$deepCopy = new DeepCopy();
$deepCopy->addFilter(new SetNullFilter(), new PropertyNameMatcher('id'));
$myCopy = $deepCopy->copy($myObject);

echo $myCopy->id; // null
```

#### `KeepFilter`

If you want a property to remain untouched (for example, an association to an object):

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\KeepFilter;
use DeepCopy\Matcher\PropertyMatcher;

$deepCopy = new DeepCopy();
$deepCopy->addFilter(new KeepFilter(), new PropertyMatcher('MyClass', 'category'));
$myCopy = $deepCopy->copy($myObject);

// $myCopy->category has not been touched
```

#### `ReplaceFilter`

  1. If you want to replace the value of a property:

  ```php
  use DeepCopy\DeepCopy;
  use DeepCopy\Filter\ReplaceFilter;
  use DeepCopy\Matcher\PropertyMatcher;

  $deepCopy = new DeepCopy();
  $callback = function ($currentValue) {
      return $currentValue . ' (copy)'
  };
  $deepCopy->addFilter(new ReplaceFilter($callback), new PropertyMatcher('MyClass', 'title'));
  $myCopy = $deepCopy->copy($myObject);

  // $myCopy->title will contain the data returned by the callback, e.g. 'The title (copy)'
  ```

  2. If you want to replace whole element:

  ```php
  use DeepCopy\DeepCopy;
  use DeepCopy\TypeFilter\ReplaceFilter;
  use DeepCopy\TypeMatcher\TypeMatcher;

  $deepCopy = new DeepCopy();
  $callback = function (MyClass $myClass) {
      return get_class($myClass);
  };
  $deepCopy->addTypeFilter(new ReplaceFilter($callback), new TypeMatcher('MyClass'));
  $myCopy = $deepCopy->copy(array(new MyClass, 'some string', new MyClass));

  // $myCopy will contain ['MyClass', 'some string', 'MyClass']
  ```


The `$callback` parameter of the `ReplaceFilter` constructor accepts any PHP callable.

#### `ShallowCopyFilter`

Stop *DeepCopy* from recursively copying element, using standard `clone` instead:

```php
use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\ShallowCopyFilter;
use DeepCopy\TypeMatcher\TypeMatcher;
use Mockery as m;

$this->deepCopy = new DeepCopy();
$this->deepCopy->addTypeFilter(
	new ShallowCopyFilter,
	new TypeMatcher(m\MockInterface::class)
);

$myServiceWithMocks = new MyService(m::mock(MyDependency1::class), m::mock(MyDependency2::class));
// all mocks will be just cloned, not deep-copied
```

#### `DoctrineCollectionFilter`

If you use Doctrine and want to copy an entity, you will need to use the `DoctrineCollectionFilter`:

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineCollectionFilter;
use DeepCopy\Matcher\PropertyTypeMatcher;

$deepCopy = new DeepCopy();
$deepCopy->addFilter(new DoctrineCollectionFilter(), new PropertyTypeMatcher('Doctrine\Common\Collections\Collection'));
$myCopy = $deepCopy->copy($myObject);
```

#### `DoctrineEmptyCollectionFilter`

If you use Doctrine and want to copy an entity who contains a `Collection` that you want to be reset, you can use the `DoctrineEmptyCollectionFilter`

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineEmptyCollectionFilter;
use DeepCopy\Matcher\PropertyMatcher;

$deepCopy = new DeepCopy();
$deepCopy->addFilter(new DoctrineEmptyCollectionFilter(), new PropertyMatcher('MyClass', 'myProperty'));
$myCopy = $deepCopy->copy($myObject);

// $myCopy->myProperty will return an empty collection
```

#### `DoctrineProxyFilter`

If you use Doctrine and use cloning on lazy loaded entities, you might encounter errors mentioning missing fields on a
Doctrine proxy class (...\\\_\_CG\_\_\Proxy).
You can use the `DoctrineProxyFilter` to load the actual entity behind the Doctrine proxy class.
**Make sure, though, to put this as one of your very first filters in the filter chain so that the entity is loaded before other filters are applied!**

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineProxyFilter;
use DeepCopy\Matcher\Doctrine\DoctrineProxyMatcher;

$deepCopy = new DeepCopy();
$deepCopy->addFilter(new DoctrineProxyFilter(), new DoctrineProxyMatcher());
$myCopy = $deepCopy->copy($myObject);

// $myCopy should now contain a clone of all entities, including those that were not yet fully loaded.
```

## Contributing

DeepCopy is distributed under the MIT license.

### Tests

Running the tests is simple:

```php
phpunit
```
