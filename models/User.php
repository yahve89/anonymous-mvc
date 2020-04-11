<?php 

namespace App\Models;

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
     * @return null | string 
     */
    public static function getRole()
    {
        $model = self::getUser();
        return empty($model)? null: $model->role;
    }
    
    /**
     * Проверка, гость ли это
     * @return bool 
     */
    public static function isGuest()
    {
        return (self::getRole() === null)? true: false;
    }
    
    /**
     * Проверка, админ ли это
     * @return bool 
     */
    public static function isAdmin()
    {
        return (self::getRole() === 'root')? true: false;
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
