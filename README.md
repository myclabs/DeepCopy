# DeepCopy

DeepCopy helps you create deep copies (clones) of your objects. It is designed to handle cycles in the association graph.


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


## Contributing

DeepCopy is distributed under the MIT license.

### Tests

Running the tests is simple:

```php
phpunit
```
