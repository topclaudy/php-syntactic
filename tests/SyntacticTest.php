<?php

use Sankofa\Syntactic\Syntacticable;

class SyntacticTest extends PHPUnit_Framework_TestCase {

    use Syntacticable;

    public function foo($a, $b = 2, $c = 3, $d = 4)
    {
        return $a * $b * $c * $d;
    }

    public static function bar($a = 1, $b = 2, $c = 3, $d = 4)
    {
        return $a * $b * $c * $d;
    }

    public function testSyntactic()
    {
        //Call function
        if(function_exists('__foo__')){
            $this->assertTrue(s('__foo__')->in('b', 5)->out() == 60);
            $this->assertTrue(s('__foo__')->in(1, 5)->out() == 60);
            $this->assertTrue(s('__foo__')->b(5)->out() == 60);
        }

        //Call instance method
        $this->assertTrue(s($this)->foo->in('c', 8)->in('a', 1)->out() == 64);
        $this->assertTrue(s($this)->foo->in(0, 1)->in(2, 8)->out() == 64);
        $this->assertTrue(s($this)->foo->a(1)->c(8)->out() == 64);

        //Call class method
        $this->assertTrue(s(static::class)->bar->in('b', 5)->out() == 60);
        $this->assertTrue(s(static::class)->bar->in(1, 5)->out() == 60);
        $this->assertTrue(s(static::class)->bar->b(5)->out() == 60);

        //Instantiate object
        $bar = s('Bar')->newInstance()->b(6)->d(8)->out();
        $this->assertTrue($bar->getProduct() == 144);

        //Calling method using array of named or indexed parameters
        $this->assertTrue(s($this)->foo(array('a'=>8, 'c'=>6)) == 384);
        $this->assertTrue(s(static::class)->bar(array('a'=>8, 'c'=>6)) == 384);

        //Call with argument passed by reference
        $t = array(1, 6, 9, 3, 8, 2);
        s('sort')->inRef(0, $t)->out();
        $this->assertTrue($t == array(1, 2, 3, 6, 8, 9));

        //Call via trait
        $this->assertTrue($this->foo->in('a', 1)->in('c', 8)->out() == 64);
        $this->assertTrue($this->foo->in(0, 1)->in(2, 8)->out() == 64);
        $this->assertTrue($this->foo->a(1)->c(8)->out() == 64);
    }
}