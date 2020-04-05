<?php 

namespace App\Basic;

use App\Helpers\Main;

class Application
{
    use TraitConfig;

    private $controller = 'App\\Controllers\\DefaultController';
    private $action = 'actionIndex';
    private $config = [];
    private $params = [];

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
                
                Main::exception('Page not found', 404);
            }
        } else {
            $this->route();            
        }
    }
    
    /**
     * Метод вызывает нужный контроллер
     */
    public function route()
    {
        if (class_exists($this->controller)) {
            $controller = new $this->controller($this->action);

            if (method_exists($controller, $this->action)) {
                $controller->{$this->action}($this->params);
            } else {
                Main::exception('Page not found', 404);
            }
        } else {
            Main::exception('Page not found', 404);
        }
    }

    /**
     * @param string $controller 
     */
    public function setController($controller)
    {
        $this->controller = 'App\\Controllers\\';
        $this->controller .= ucfirst($controller);
        $this->controller .= 'Controller';
        return $this;
    }

    /**
     * @param string $key 
     * @param string $param 
     */
    public function setParams($key, $param)
    {
        $this->params[$key] = $param;
        return $this;
    }

    /**
     * @param string $action 
     */
    public function setAction($action)
    {
        $this->action = 'action';
        $this->action .= ucfirst($action);
        return $this;
    }
}
