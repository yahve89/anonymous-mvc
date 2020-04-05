<?php 

namespace App\Basic;

class DataBase extends \PDO
{
    use TraitConfig;

    private static $db;
    
    public function __construct()
    {
        $config = $this->config('db');
        parent::__construct($config['dsn'], $config['username'], $config['password']);
    }

    public static function db()
    {
        if (!isset(self::$db))
            self::$db = new self;
        
        self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        return self::$db;
    }
}
