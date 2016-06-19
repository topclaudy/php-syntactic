<?php

namespace Sankofa\Syntactic;

/**
 * Class SyntacticObject
 * @package Sankofa\Syntactic
 */
class SyntacticObject extends SyntacticClassAbstract
{

    /**
     * SyntacticObject constructor.
     *
     * @param $object
     *
     * @throws SyntacticException
     */
    public function __construct($object)
    {
        if( is_object($object) ){
            $this->classOrObject = $object;
        } else {
            throw new SyntacticException("You should pass an object!");
        }
    }
}