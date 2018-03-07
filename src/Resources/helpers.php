<?php

/*
|--------------------------------------------------------------------------
| Syntactic Helpers
|--------------------------------------------------------------------------
|
|
*/

use Awobaz\Syntactic\Syntactic;

if (!function_exists('syntactic')) {
    /**
     * @author Claudin J. Daniel <jclaudindaniel@gmail.com>
     * @param $target
     * @return \Awobaz\Syntactic\SyntacticClass|\Awobaz\Syntactic\SyntacticFunction|\Awobaz\Syntactic\SyntacticObject
     * @throws \Awobaz\Syntactic\SyntacticException
     */
    function syntactic($target){
        return Syntactic::make($target);
    }
}

if (!function_exists('s')) {
    /**
     * @author Claudin J. Daniel <jclaudindaniel@gmail.com>
     * @param $target
     * @return \Awobaz\Syntactic\SyntacticClass|\Awobaz\Syntactic\SyntacticFunction|\Awobaz\Syntactic\SyntacticObject
     * @throws \Awobaz\Syntactic\SyntacticException
     */
    function s($target){
        return Syntactic::make($target);
    }
}