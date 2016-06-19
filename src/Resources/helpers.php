<?php

/*
|--------------------------------------------------------------------------
| Syntactic Helpers
|--------------------------------------------------------------------------
|
|
*/

use Sankofa\Syntactic\Syntactic;

if (!function_exists('syntactic')) {
    /**
     * @author Claudin J. Daniel <jclaudindaniel@gmail.com>
     * @param $target
     * @return \Sankofa\Syntactic\SyntacticClass|\Sankofa\Syntactic\SyntacticFunction|\Sankofa\Syntactic\SyntacticObject
     * @throws \Sankofa\Syntactic\SyntacticException
     */
    function syntactic($target){
        return Syntactic::make($target);
    }
}

if (!function_exists('s')) {
    /**
     * @author Claudin J. Daniel <jclaudindaniel@gmail.com>
     * @param $target
     * @return \Sankofa\Syntactic\SyntacticClass|\Sankofa\Syntactic\SyntacticFunction|\Sankofa\Syntactic\SyntacticObject
     * @throws \Sankofa\Syntactic\SyntacticException
     */
    function s($target){
        return Syntactic::make($target);
    }
}