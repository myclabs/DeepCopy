# DeepCopy

DeepCopy helps you create deep copies (clones) of your objects. It is designed to handle cycles in the association graph.

## Why?

TODO

## How?

Composer:

```json
	"require": {
		"myclabs/deep-copy": "*"
	}
```

```php
$deepCopy = new DeepCopy();
$myCopy = $deepCopy->copy($myObject);
```

## Contribute

DeepCopy is distributed under the MIT license.

### Tests

Running the tests is simple:

```php
phpunit
```
