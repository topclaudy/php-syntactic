<?php

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