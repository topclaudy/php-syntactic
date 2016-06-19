<?php

namespace Sankofa\Syntactic;

/**
 * Class SyntacticClass
 * @package Sankofa\Syntactic
 */
class SyntacticClass extends SyntacticClassAbstract
{

    /**
     * SyntacticClass constructor.
     *
     * @param $class
     *
     * @throws SyntacticException
     */
    public function __construct($class)
    {
        if( class_exists($class) ){
            $this->classOrObject = $class;
        } else {
            throw new SyntacticException("Class not found!");
        }
    }
}