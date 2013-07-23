# DeepCopy

DeepCopy helps you create deep copies (clones) of your objects. It is designed to handle cycles in the association graph.

[![Build Status](https://travis-ci.org/myclabs/DeepCopy.png?branch=master)](https://travis-ci.org/myclabs/DeepCopy) [![Coverage Status](https://coveralls.io/repos/myclabs/DeepCopy/badge.png?branch=master)](https://coveralls.io/r/myclabs/DeepCopy?branch=master)


## How?

Install with Composer:

```json
{
	"require": {
		"myclabs/deep-copy": "*"
	}
}
```

Use simply:

```php
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

### With DeepCopy

![With DeepCopy](doc/deep-copy.png)


## How it works

DeepCopy traverses recursively all your object's properties and clones them.

To avoid cloning the same object twice (and thus, keep you object graph), it keeps a hash-map of all instances.


## Going further

You can add filters to customize the copy process.

The method to add a filter is `$deepCopy->addFilter($className, $propertyName, $filter);` with `$filter` implementing `DeepCopy\Filter\Filter`.

We provide some generic filters.

#### `SetNullFilter`

Let's say for example that you are copying a database record (or a Doctrine entity), so you want the copy not to have any ID:

```php
$myObject = MyClass::load(123);
echo $myObject->id; // 123

$deepCopy = new DeepCopy();
$deepCopy->addFilter('MyClass', 'id', new SetNullFilter());
$myCopy = $deepCopy->copy($myObject);

echo $myCopy->id; // null
```

#### `KeepFilter`

If you want a property to remain untouched (for example, an association to an object):

```php
$deepCopy = new DeepCopy();
$deepCopy->addFilter('MyClass', 'category', new KeepFilter());
$myCopy = $deepCopy->copy($myObject);

// $myCopy->category has not been touched
```


## Contributing

DeepCopy is distributed under the MIT license.

### Tests

Running the tests is simple:

```php
phpunit
```
