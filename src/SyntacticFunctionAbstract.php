<?php

namespace Sankofa\Syntactic;

/**
 * Class SyntacticFunctionAbstract
 * @package Sankofa\Syntactic
 */
abstract class SyntacticFunctionAbstract
{
    protected $userParams = array();
    protected $callParams = array();
    protected $reflectionParameters = array();
    protected $callTarget = null;

    /**
     * @param $paramNameOrPosition
     * @param $value
     * @throws SyntacticException
     */
    protected function addUserParam($paramNameOrPosition, &$value){
        $reflectionParameter = null;

        foreach($this->reflectionParameters as $aReflectionParameter){
            if($aReflectionParameter->getName() === $paramNameOrPosition || $aReflectionParameter->getPosition() === $paramNameOrPosition){
                $reflectionParameter = $aReflectionParameter;
                break;
            }
        }

        if($reflectionParameter){
            if($reflectionParameter->isPassedByReference()) {
                $this->userParams[$paramNameOrPosition] = &$value;
            } else {
                $this->userParams[$paramNameOrPosition] = $value;
            }
        } else {
            if(is_int($paramNameOrPosition)) {
                throw new SyntacticException("Invalid parameter position!");
            } else {
                throw new SyntacticException("Invalid parameter name!");
            }
        }
    }

    public function addUserParams(array $params)
    {
        foreach($params as $nameOrPosition=>$param){
            $this->addUserParam($nameOrPosition, $param);
        }
    }

    /**
     * @param array $params
     */
    public function setParameters(array $params){
        $this->reflectionParameters = $params;
    }


    /**
     * @throws SyntacticException
     */
    private function buildCallParams()
    {
        foreach ($this->reflectionParameters as $param) {
            if (array_key_exists($param->getName(), $this->userParams)) {
                if($param->isPassedByReference()) {
                    $this->callParams[$param->getPosition()] = &$this->userParams[$param->getName()];
                } else {
                    $this->callParams[$param->getPosition()] = $this->userParams[$param->getName()];
                }
            } elseif (array_key_exists($param->getPosition(), $this->userParams)) {
                if($param->isPassedByReference()) {
                    $this->callParams[$param->getPosition()] = &$this->userParams[$param->getPosition()];
                } else {
                    $this->callParams[$param->getPosition()] = $this->userParams[$param->getPosition()];
                }
            }  else {
                if($param->isOptional()) {
                    if ($param->isDefaultValueAvailable()) {
                        $this->callParams[$param->getPosition()] = $param->getDefaultValue();
                    }
                } else {
                    throw new SyntacticException("The parameter ".$param->getName()." at position ".$param->getPosition()." is required!");
                }
            }
        }
    }


    /**
     * @param $name
     * @param $args
     *
     * @return $this|mixed
     * @throws SyntacticException
     */
    public function __call($name, $args){
        $this->addUserParam($name, $args[0]);

        return $this;
    }

    /**
     * @param $paramNameOrPosition
     * @param $value
     * @return $this
     * @throws SyntacticException
     */
    public function in($paramNameOrPosition, $value){
        $this->addUserParam($paramNameOrPosition, $value);

        return $this;
    }

    /**
     * @param $paramNameOrPosition
     * @param $ref
     * @return $this
     * @throws SyntacticException
     */
    public function inRef($paramNameOrPosition, &$ref){
        $this->addUserParam($paramNameOrPosition, $ref);

        return $this;
    }


    /**
     * @param $paramNameOrPosition
     * @param $value
     *
     * @return $this
     * @throws SyntacticException
     */
    public function with($paramNameOrPosition, $value){
        return $this->in($paramNameOrPosition, $value);
    }


    /**
     * @param $paramNameOrPosition
     * @param $ref
     *
     * @return SyntacticFunctionAbstract
     */
    public function withRef($paramNameOrPosition, &$ref){
        return $this->inRef($paramNameOrPosition, $ref);
    }


    /**
     * @return mixed
     */
    public function out(){
        $this->buildCallParams();

        return call_user_func_array($this->callTarget, $this->callParams);
    }
}