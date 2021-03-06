<?php 

namespace App\Basic;

use App\Helpers\Main; 
use App\Models\User;
use App\Helpers\Exception;

class Controller
{
    public static $layout = 'layout/main';
    public static $session = false;  

    public function __construct() 
    {
        $this->checkAccess();

        if (self::$session == false)
            self::$session = Session::getInstance();
    }

    /**
     * Метод проверяет разрешен ли доступ к экшену
     * @return void|Exception
     */
    public function checkAccess()
    {
        $forbidden = true;
        $userRole = User::getRole();
        $actionCalled = App::self()->nameAction();
        
        foreach ($this->access() as $role => $actions) {
            if (strcasecmp($userRole, $role) == 0) {
                foreach ($actions as $action) {          
                    if (strcasecmp($actionCalled, $action) == 0)
                        $forbidden = false;
                } 
            }
        }

        if ($forbidden)
            Exception::set('Forbidden', 403);
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
        $fileView = App::self()->config('alias.viewDir') .$view; 
        
        if ($layout == true)
            $fileView = App::self()->config('alias.viewDir') .self::$layout; 

        return require_once $fileView . '.php';
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