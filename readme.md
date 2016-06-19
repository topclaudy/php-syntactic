# PHP Syntactic Sugar for named and indexed parameters call.

PHP lacks support for named parameters call. **Syntactic** brings named and indexed parameter call support for user
defined functions and methods of user defined classes (including class constructor).

**Syntactic** allows you to specify parameters in arbitrary order by name or position/index when calling functions and methods
without worrying about default values for their parameters (skipping the ones you don't want to specify). This would
allow to define functions and methods which could accept wider ranges of parameters without resorting to using arrays and
complicated checks. The parameters/arguments of your functions and methods remain documentable using standard PHPDoc notation.


## Installation

The recommended way to install **Syntactic** is through [Composer](http://getcomposer.org/)

```bash
$ composer require sankofa/syntactic
```
**_Syntactic_** requires PHP 5.4 or later.

## Usage

**Syntactic** provides helpers/wrappers functions and a trait to make your functions and methods _syntacticable_.
After installation the _**syntactic**_ and _**s**_ helpers are available (_**s**_ is an alias for _**syntactic**_). Those who
prefer trait may use the _**Syntacticable**_ trait.

The helpers create _syntacticable_ objects. You can then use the methods _**in**_ and _**inRef**_ to pass (input) parameters by
value or reference. The method _**out**_ is used to execute the function/method. The methods _**in**_, **_inRef_**, _**out**_
are chainable but the method _**out**_ should always be the last call in the chain (see examples below).

The methods **_with_** and **_withRef_** are aliases for _**in**_ and **_inRef_** respectively.


### Usage with user defined functions

Consider the following function which computes the product of its 4 arguments (a, b, c and d):
```php
<?php

function foo($a = 1, $b = 2, $c = 3, $d = 4){
    return $a * $b * $c * $d;
}
```
With _Syntactic_, you may call this function passing and skipping arguments using a chainable syntax:

```php
//Call with argument b only
echo s('foo')->in('b', 5)->out(); //Outputs 60

//Call with argument a and argument at index/position 1 (b),
echo s('foo')->in('a', 7)->in(1, 5)->out(); //Outputs 420

//Call with argument c only through dynamic method
echo s('foo')->c(9)->out(); //Outputs 72
```

#### Explanation

Let's explain the construction ```s('foo')->in('a', 7)->in(1, 5)->out();```

1. We use the helper _**s**_, ```s('foo')```, to create a _syntacticable_ object from the function _foo_. The _**s**_ helper accepts the name of a function, an
object or the name of a class as argument. Here we pass the name of the function _foo_ as a string.

2. We call the method **_in_** to specify the arguments we want without worrying about the order and default value of the other arguments.
(use **_inRef_** or **_withRef_** to pass argument by reference). Note that we can refer to arguments by their name  or position (as defined in the function signature).
Here we pass the parameter _$a_ using its name, and the parameter _$b_ using its position. Position/index starts at 0 (zero).

3. We finally call the method **_out_** to execute the function _foo_.

For convenience, parameters names are also exposed as methods. The construction:

```s('foo')->in('a', 7)->in(1, 5)->out();```

is equivalent to:

```s('foo')->a(7)->b(5)->out();```

### Usage with object/class methods.

The syntax for object/class methods is a bit different. You must refer to the methods as object/class property before you
call **_in_** (or **_inRef_**) to pass parameters.

```php
<?php

class MyClass {
	public function foo($a = 1, $b = 2, $c = 3, $d = 4){
		return $a * $b * $c * $d;
	}

	public static function bar($a = 1, $b = 2, $c = 3, $d = 4){
		return $a * $b * $c * $d;
	}
}

$myObj = new MyClass();

//Call the instance method foo
echo s($myObj)->foo->in('c', 8)->in('a', 1)->out(); //Outputs 64
echo s($myObj)->foo->in(0, 1)->in(2, 8)->out(); //Outputs 64
echo s($myObj)->foo->a(1)->c(8)->out(); //Outputs 64

//Call the static method bar
echo s('MyClass')->bar->in('b', 5)->out(); //Outputs 60
echo s('MyClass')->bar->in(1, 5)->out(); //Outputs 60
echo s('MyClass')->bar->b(5)->out(); //Outputs 60
```
When calling static methods, it is recommended to use the fully qualified name of the class if you use namespaces.

#### Using with class constructor

Consider the following class:

```
class Bar
{
    protected $a;
    protected $b;
    protected $c;
    protected $d;

    public function __construct($a = 1, $b = 2, $c = 3, $d = 4)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $this->d = $d;
    }

    public function getProduct(){
        return $this->a * $this->b * $this->c * $this->d;
    }
}
```

We can instantiate new object of this class using the _**newInstance**_ method and only specify the arguments we want for
the constructor in an arbitrary order:

```
//Creates and instance of Bar
$obj = s('Bar')->newInstance()->b(6)->d(8)->out();
//Call a method on the instance
echo $obj->getProduct(); //Outputs 144
```

#### Calling methods directly on _syntacticable_ objects

You may call a method directly on a syntacticable object by passing an array of named or indexed parameters.

Consider the following class:

```
class FooBar
{
    public function foo($a, $b = 2, $c = 3, $d = 4)
	{
		return $a * $b * $c * $d;
	}

	public static function bar($a, $b = 2, $c = 3, $d = 4)
	{
		return $a * $b * $c * $d;
	}

	public static function hello(){
		echo "Hello World!";
	}
}
```

We can call the method _foo_ on in instance:

```
$obj = new FooBar();
echo s($obj)->foo(array('a'=>5, 'c'=>4)); //Outputs 160
```

We can call the static method _bar_:

```
echo s('FooBar')->bar(array('a'=>5, 'c'=>4)); //Outputs 160
```

Methods without arguments should be called without arguments:

```
echo s('FooBar')->hello(); //Prints 'Hello World!"
```

### Using the _Syntacticable_ trait

When using the _Syntacticable_ trait, all the instance methods of the class become _syntacticable_. This is useful when you don't want
to use the helpers. The trait doesn't support static methods.

Keep in mind that the _Syntacticable_ trait overrides the PHP magic method ___get_; you can't use the trait if a class
overrides this method.

Example using the _Syntacticable_ trait

```php
<?php

class MyClass {
	use Sankofa\Syntactic\Syntacticable;

	public function foo($a = 1, $b = 2, $c = 3, $d = 4){
		return $a * $b * $c * $d;
	}

	public static function bar($a = 1, $b = 2, $c = 3, $d = 4){
		return $a * $b * $c * $d;
	}
}

$myObj = new MyClass();

//Call the instance method foo
echo $myObj->foo->in('c', 8)->in('a', 1)->out(); //Outputs 64
echo $myObj->foo->in(0, 1)->in(2, 8)->out(); //Outputs 64
echo $myObj->foo->a(1)->c(8)->out(); //Outputs 64
```

Thanks to the _Syntacticable_ trait, the instance methods are exposed as properties, then you can call them in a _syntacticable_ way.

## Limitations

You can use _**Syntactic**_ with internal (built-in) PHP functions, but you can't skip arguments if they are not at the
end of the arguments list of the functions. Otherwise, things will not work as expected.

The following examples will work because the optional arguments for the built-in functions _sort_ _and preg__replace are
at the end of the arguments list.

```php
$t = array(1, 6, 9, 3, 8, 2);
s('sort')->inRef(0, $t)->out();
```

```
<?php
$string = 'April 15, 2003';
$pattern = '/(\w+) (\d+), (\d+)/i';
$replacement = '${1}1,$3';
$count = 0;
echo s('preg_replace')->in(0, $pattern)->in(1, $replacement)->in(2, $string)->out();
```

while the example below won't work.

```
<?php
$string = 'April 15, 2003';
$pattern = '/(\w+) (\d+), (\d+)/i';
$replacement = '${1}1,$3';
$count = 0;
echo s('preg_replace')->in(0, $pattern)->in(1, $replacement)->in(2, $string)->inRef(4, $count)->out();
```
For this to work, we need to specify the value for the optional parameter _limit_ (at index 3):

```
echo s('preg_replace')->in(0, $pattern)->in(1, $replacement)->in(2, $string)->in(3, 1)->inRef(4, $count)->out();
```

Due to PHP's Reflection implementation details, it is not possible to get the default value of built-in functions or
methods of built-in classes. We strongly recommend to use _Syntactic_ only with user defined functions and methods of
user defined classes.

## Contributing

Thank you for considering contributing to **Syntactic**! Pull requests are welcomed!

The following steps are recommended to contribute:

* Fork this repository.
* Add new features, fix bug or bring improvements.
* Run tests
* Make a pull request.

### Contributor Code of Conduct

As contributors and maintainers of this project, we pledge to respect all people who contribute through reporting issues,
posting feature requests, updating documentation, submitting pull requests or patches, and other activities.

We are committed to making participation in this project a harassment-free experience for everyone, regardless of level
of experience, gender, gender identity and expression, sexual orientation, disability, personal appearance, body size,
race, age, or religion.

Examples of unacceptable behavior by participants include the use of sexual language or imagery, derogatory comments or
personal attacks, trolling, public or private harassment, insults, or other unprofessional conduct.

Project maintainers have the right and responsibility to remove, edit, or reject comments, commits, code, wiki edits,
issues, and other contributions that are not aligned to this Code of Conduct. Project maintainers who do not follow the
Code of Conduct may be removed from the project team.

Instances of abusive, harassing, or otherwise unacceptable behavior may be reported by opening an issue or contacting one
or more of the project maintainers.

This Code of Conduct is adapted from the [Contributor
Covenant](http:contributor-covenant.org), version 1.0.0, available at
[http://contributor-covenant.org/version/1/0/0/](http://contributor-covenant.org/version/1/0/0/)


## Unit Tests

In order to run the test suite, install the development dependencies:

```
$ composer install --dev
```

Then, run the following command:

```
$ vendor/bin/phpunit
```

## Authors

* [Claudin J. Daniel](https://github.com/topclaudy)

## License

**Syntactic** is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).




