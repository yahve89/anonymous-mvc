<?php 

namespace App\Helpers;

class Exception
{
    /**
     * @param string $msg
     * @param string $msg
     * @return string
     */
    public static function set($msg = false, $code = false)
    {
        header("HTTP/1.0 $code $msg");
        echo $msg;
        exit();
    }
}
