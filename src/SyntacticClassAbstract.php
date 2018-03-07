<?php

namespace Awobaz\Syntactic;

use ReflectionClass;

/**
 * Class SyntacticClassAbstract
 * @package Awobaz\Syntactic
 */
abstract class SyntacticClassAbstract
{
    protected $classOrObject = null;

    /**
     * @param $name
     * @return SyntacticMethod
     */
    public function __get($name){
        return new SyntacticMethod($this->classOrObject, $name);
    }


    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws SyntacticException
     */
    public function __call($name, $arguments)
    {
        if(count($arguments) <= 1) {
            if (method_exists($this->classOrObject, $name)) {
                if(count($arguments) == 0){
                    return call_user_func_array(array($this->classOrObject, $name), array());
                } else {
                    $userParams = $arguments[0];
                    if( is_array($userParams) ) {
                        $syntacticMethod = new SyntacticMethod($this->classOrObject, $name);
                        $syntacticMethod->addUserParams($userParams);
                        return $syntacticMethod->out();
                    } else {
                        throw new SyntacticException("You should specify an array of named or indexed parameters!");
                    }
                }
            }
        } else {
            throw new SyntacticException("Too many parameters! You should specify only an array of named or indexed parameters");
        }
    }


    /**
     * @return object|SyntacticMethod
     * @throws SyntacticException
     */
    public function newInstance(){
        $reflectionClass = new ReflectionClass($this->classOrObject);

        if(method_exists($this->classOrObject, '__construct')) {

            $constructor = $reflectionClass->getConstructor();

            if( count($constructor->getParameters()) == 0){
                return $reflectionClass->newInstanceWithoutConstructor();
            }

            $instantiator = new SyntacticMethod($reflectionClass, 'newInstance');
            $instantiator->setParameters($constructor->getParameters());

            return $instantiator;
        } elseif($reflectionClass->isInstantiable()) {
            return $reflectionClass->newInstanceWithoutConstructor();
        } else {
            throw new SyntacticException("The class '".$reflectionClass->getShortName()."' is not instantiable.");
        }
    }
}