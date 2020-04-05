<?php 

namespace App\Basic;

class Session
{
    const SESSION_STARTED = TRUE;
    const SESSION_NOT_STARTED = FALSE;
   
    private $sessionState = self::SESSION_NOT_STARTED;
    private static $instance;
   
    /**
     * @return object
     */
    public static function getInstance()
    {
        if ( !isset(self::$instance)) {
            self::$instance = new self;
        }
       
        self::$instance->startSession();
        return self::$instance;
    }
   
    /**
     * @return bool
     */
    public function startSession()
    {
        if ( $this->sessionState == self::SESSION_NOT_STARTED ) {
            $this->sessionState = session_start();
        }
       
        return $this->sessionState;
    }
   
    /**  
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set( $name , $value )
    {
        $_SESSION[$name] = $value;
    }
   
    /**  
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($_SESSION[$name]))
            return $_SESSION[$name];
    }

    /**  
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }
   
    /**  
     * @param string $name
     * @return void
     */
    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }
    
    /**  
     * @return void
     */
    public function destroy()
    {
        if ( $this->sessionState == self::SESSION_STARTED ) {
            $this->sessionState = !session_destroy();
            unset($_SESSION);
           
            return !$this->sessionState;
        }
       
        return false;
    }
}