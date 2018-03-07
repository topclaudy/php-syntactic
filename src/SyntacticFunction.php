<?php

namespace Awobaz\Syntactic;

use ReflectionFunction;

/**
 * Class SyntacticFunction
 * @package Awobaz\Syntactic
 */
class SyntacticFunction extends SyntacticFunctionAbstract
{
    /**
     * SyntacticFunction constructor.
     *
     * @param $function
     *
     * @throws SyntacticException
     */
    public function __construct($function)
    {
        if( !is_string($function) ){
            throw new SyntacticException("You should pass a function name!");
        }

        if(function_exists($function)){
            $reflectionFunction = new ReflectionFunction($function);
            $this->reflectionParameters = $reflectionFunction->getParameters();
            $this->callTarget = $function;
        } else {
            throw new SyntacticException("Function does not exist!");
        }
    }
}