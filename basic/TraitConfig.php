<?php 

namespace App\Basic;

trait TraitConfig
{
    /**
     * @param string $name 
     * @return array
     */
    public function config($name)
    {
        $config = require dirname(__DIR__) . '/config/web.php';
 
        return $config[$name];
    }
}