<?php

if (!function_exists('__foo__')) {
    function __foo__($a = 1, $b = 2, $c = 3, $d = 4)
    {
        return $a * $b * $c * $d;
    }
}

