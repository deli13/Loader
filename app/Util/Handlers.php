<?php


namespace deli13\Loader\Util;


class Handlers
{

    public static function setExceptionHandler(\Closure $func)
    {
        $old_handler = set_exception_handler($func);
    }

    public static function setErrorHandler(\Closure $func)
    {
        $func_handler = function ($num, $str, $file, $line, $context = null) use ($func) {
            $func(new \ErrorException($str,0,$num,$file,$line));
        };
        set_error_handler($func_handler);
    }
}
