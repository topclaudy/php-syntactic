<?php

namespace Sankofa\Syntactic;

/**
 * Class Syntactic
 * @package Sankofa\Syntactic
 */
abstract class Syntactic
{
    /**
     * @param $target
     * @return SyntacticClass|SyntacticFunction|SyntacticObject
     * @throws SyntacticException
     */
    public static function make($target){
        if(is_object($target)) {
            return new SyntacticObject($target);
        } elseif( class_exists($target) ){
            return new SyntacticClass($target);
        } elseif(function_exists($target)){
            return new SyntacticFunction($target);
        } else {
            throw new SyntacticException("Invalid syntactic argument! Argument should be an object or a string. The string must be a class name or a defined function");
        }
    }
}