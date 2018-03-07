<?php
namespace Awobaz\Syntactic;

/**
 * Trait Syntacticable
 * @package Awobaz\Syntactic
 */
trait Syntacticable
{
    /**
     * @param $name
     * @return SyntacticMethod
     */
    public function __get($name){
        return new SyntacticMethod($this, $name);
    }
}