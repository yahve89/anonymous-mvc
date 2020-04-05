<?php 

namespace App\Models;

use \App\Basic\Session;

class User extends \App\Basic\Model
{    
    public function __construct($model = null) 
    {
        if ($model === null)
            $model = $this->table('user');

        $this->setModel($model);
    }

    /**
     * Поиск пользователя по username
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $sql = 'SELECT id, username, password, role FROM user WHERE username = :username';
        $sth = self::db()->prepare($sql);
        $sth->execute([':username' => $username]);
        $model = $sth->fetch();
        return empty($model)? null: new static($model);
    }

    /**
     * Получить модель текущего пользователя
     * @param string $username
     * @return static|null
     */
    public static function getUser()
    {
        $sql = 'SELECT id, username, password, role FROM user WHERE id = :id';
        $sth = self::db()->prepare($sql);
        $sth->execute([':id' => \App\Basic\Session::getInstance()->identity]);
        $model = $sth->fetch();
        return empty($model)? null: new static($model);
    }

    /**
     * Получить роль текущего пользователя
     * @return type
     */
    public static function getRole()
    {
        $model = self::getUser();
        return empty($model)? null: $model->role;
    }

    /**
     * Валидация пароля
     * @param string $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }
}
