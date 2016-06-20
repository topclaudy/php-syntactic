<?php

namespace Sankofa\Syntactic;

use ReflectionMethod;

/**
 * Class SyntacticMethod
 * @package Sankofa\Syntactic
 */
class SyntacticMethod extends SyntacticFunctionAbstract
{

    /**
     * SyntacticMethod constructor.
     *
     * @param $classOrObject
     * @param $method
     *
     * @throws SyntacticException
     */
    public function __construct($classOrObject, $method)
    {
        if( ! (is_object($classOrObject) || class_exists($classOrObject)) ){
            throw new SyntacticException("You should pass an object or a class!");
        }

        if(method_exists($classOrObject, $method)){
            $reflectionMethod = new ReflectionMethod($classOrObject, $method);
            $this->reflectionParameters = $reflectionMethod->getParameters();
            $this->callTarget = array($classOrObject, $method);
        } else {
            throw new SyntacticException("Method '".$method."' does not exist!");
        }
    }
}