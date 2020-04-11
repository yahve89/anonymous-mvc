<?php 

namespace App\Basic;

class App
{
    private $controller = 'App\\Controllers\\DefaultController';
    private $action = 'actionIndex';
    private $config = [];
    private $params = [];
    private static $self = null;

    /**
     * @return object
     */
    public static function self()
    {
        if ( !isset(self::$self)) {
            self::$self = new self;
        }

        return self::$self;
    }

    /**
     * Метод проверяет наличие маршрута и сравнивает его с имеющимися
     */
    public function run()
    {
        if (!empty($_REQUEST['r'])) {
            if (!empty($this->config('route'))) {
                foreach ($this->config('route') as $re => $route) {
                    preg_match_all($re, $_REQUEST['r'], $matches, PREG_SET_ORDER, 0);
                 
                    if (!empty($matches)) {
                        $key = 1;
                        foreach ($this->config('route')[$re] as $i => $attr) {                  
                            if (method_exists($this, $attr)) {     
                                $this->{$attr}($matches[0][$key]);
                            } else {
                                $this->setParams($attr, $matches[0][$key]);
                            }

                            $key++;
                        }

                        return $this->route();
                    }
                }
                
                Exception::set('Page not found', 404);
            }
        } else {
            $this->route();            
        }
    }
    
    /**
     * Метод вызывает нужный контроллер
     */
    private function route()
    {
        if (class_exists($this->controller)) {
            $controller = new $this->controller();

            if (method_exists($controller, $this->action)) {
                $controller->{$this->action}($this->params);
            } else {
                Exception::set('Page not found', 404);
            }
        } else {
            Exception::set('Page not found', 404);
        }
    }

    /**
     * @param string $controller 
     */
    private function setController($controller)
    {
        $this->nameController = ucfirst($controller);
        $this->controller = 'App\\Controllers\\';
        $this->controller .= ucfirst($controller);
        $this->controller .= 'Controller';
        return $this;
    }

    /**
     * @param string $key 
     * @param string $param 
     */
    private function setParams($key, $param)
    {
        $this->params[$key] = $param;
        return $this;
    }
    
    /**
     * @param string $action 
     */
    private function setAction($action)
    {
        $this->action = 'action';
        $this->action .= ucfirst($action);
        return $this;
    }

    /**
     * @return type
     */
    public function nameAction()
    {
        return $this->action;
    }
    
    /**
     * @return type
     */
    public function nameController()
    {
        return lcfirst(str_replace('App\\Controllers\\', '', $this->controller));
    }

    /**
     * @param string $name 
     * @return array
     */
    public function config($name)
    {
        $params = explode('.', $name);

        if (count($params) > 1) {
            list($name, $param) = $params;
            return $this->config[$name][$param];
        }

        return $this->config[$name];
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
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
