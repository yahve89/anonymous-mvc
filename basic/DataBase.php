<?php 

namespace App\Basic;

use \Envms\FluentPDO\Query;

class DataBase extends \PDO
{
    private static $db;
    
    public function __construct()
    {
        $dsn = App::self()->config('db.dsn');
        $username = App::self()->config('db.username');
        $password = App::self()->config('db.password');
        parent::__construct($dsn, $username, $password);
    }

    public static function db()
    {
        if (!isset(self::$db))
            self::$db = new self;
        
        self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        return self::$db;
    }

    /**
     * @return type
     */
    public function fpdo()
    {
        return new Query(self::db());
    }
}
