<?php 

namespace App\Basic;

use App\Helpers\Main; 
use App\Models\User;

class Controller
{
    public static $layout = 'layout/main';
    public static $session = false;
    
    public function __construct($action) 
    {
        $this->checkAccess($action);

        if (self::$session == false)
            self::$session = \App\Basic\Session::getInstance();
    }

    /**
     * Метод проверяет разрешен ли доступ к экшену
     * @param string $actionCalled
     * @return void|Exception
     */
    public function checkAccess($actionCalled)
    {
        $forbidden = true;
        $userRole = User::getRole();

        foreach ($this->access() as $role => $actions) {
            if (strcasecmp($userRole, $role) == 0) {
                foreach ($actions as $action) {          
                    if (strcasecmp($actionCalled, $action) == 0)
                        $forbidden = false;
                } 
            }
        }

        if ($forbidden)
            Main::exception('Forbidden', 403);
    }

    /**
     * @return mixed
     */
    public function render($view, $params)
    {
        Controller::renderPartal($view, $params, true);
    }

    /**
     * @return void
     */
    public function redirect($path, $msg = false, $code = false)
    {
        if ($code !== false && $msg !== false)
            header("HTTP/1.0 $code $msg");
        
        header("Location: $path");
        exit();
    }

    /**
     * @return mixed
     */
    public static function renderPartal($view, $params = [], $layout = false)
    {
        $fileView = Main::viewDir() .$view; 
        
        if ($layout == true)
            $fileView = Main::viewDir() .self::$layout; 
        
        try {
            return require_once $fileView . '.php';
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    /**
     * @param string $class
     * @param string $msg
     * @return void
     */
    public function setFlash($class = '', $msg = '')
    {
        self::$session->flash = [$class => $msg];
    }

    /**
     * @return array
     */
    public static function getFlash()
    {
        $flash = self::$session->flash;
        unset(self::$session->flash);
        return $flash;
    }
}