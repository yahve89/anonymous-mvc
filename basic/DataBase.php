<?php 

namespace App\Basic;

class DataBase extends \PDO
{
    private static $db;
    
    public function __construct()
    {
        $dsn = \App\Basic\AI::app()->config('db.dsn');
        $username = \App\Basic\AI::app()->config('db.username');
        $password = \App\Basic\AI::app()->config('db.password');
        parent::__construct($dsn, $username, $password);
    }

    public static function db()
    {
        if (!isset(self::$db))
            self::$db = new self;
        
        self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        return self::$db;
    }
}
