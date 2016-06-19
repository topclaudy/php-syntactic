<?php
namespace Sankofa\Syntactic;

/**
 * Trait Syntacticable
 * @package Sankofa\Syntactic
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