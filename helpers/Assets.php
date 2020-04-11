<?php 

namespace App\Helpers;

use App\Basic\App;

class Assets
{
    private static $instance = null;
    private $assets;

    /**
     * @return object
     */
    public static function init()
    {
        if ( !isset(self::$instance)) {
            self::$instance = new self;
            self::$instance->load();
        }
        
        return self::$instance;
    }

    /** 
     * @return array
     */
    private function load()
    {
        $assets = require dirname(__DIR__) . '/assets/App.php';
        $this->assets = (Object) $assets[App::self()->config('projectName')];
    }

    /**
     * @return type
     */
    public static function getCss()
    {
        $links = '';

        if (!empty(self::init()->assets->css))
            foreach (self::init()->assets->css as $href) {
                $links .= '<link rel="stylesheet" href="' .$href .'">';
            }

        return $links;
    }

    /**
     * @return type
     */
    public static function getJs()
    {
        $scripts = '';

        if (!empty(self::init()->assets->js))
            foreach (self::init()->assets->js as $src) {
                $scripts .= '<script type="text/javascript" src="' .$src .'"></script>';
            }

        return $scripts;
    }

    /**
     * @param string $css
     */
    public function setCss($css)
    {
        $this->assets->css[] = $css;
        return $this;
    }    
    
    /**
     * Конструктор закрыт
     */
    private function __construct() { }

    /**
     * Клонирование запрещено
     */
    private function __clone() { }

    /**
     * Сериализация запрещена
     */
    private function __sleep() { }

    /**
     * Десериализация запрещена
     */
    private function __wakeup() { }
}