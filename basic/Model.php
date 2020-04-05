<?php 

namespace App\Basic;

use \App\Helpers\Main;

class Model extends \App\Basic\DataBase
{
    /**
     * @param array $model
     * @return object
     */
    protected function setModel($model)
    {
        foreach ($model as $key => $value) {   
            $this->$key = $value;  
        }

        return $this;
    }

    /**  
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name , $value)
    {
        $this->$name = $value;
    }
   
    /**  
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->$name))
            return $this->$name;
    }

    /**  
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->$name);
    }
   
    /**  
     * @param string $name
     * @return void
     */
    public function __unset($name)
    {
        unset($this->$name);
    }

    /**
     * Возвращает данные о таблице
     * @return array
     */
    protected function table($table)
    {
        $model = null;
        $sql = "SHOW COLUMNS FROM `$table`";
        $sth = self::db()->prepare($sql);
        $sth->execute();

        foreach ($sth->fetchAll() as $column) {
            if (strcasecmp($column->Field, 'id') !== 0)
                $model[$column->Field] = $column->Default;
        }

        return $model;
    }

    /**
     * Поиск модели по id
     * @param string $table
     * @param integer $id
     * @return static | Exception
     */
    public function findById($table, $id, $select = '*')
    {      
        $sql = "SELECT $select FROM $table WHERE id = :id";
        $sth = self::db()->prepare($sql);
        $sth->execute([':id' => $id]);
        $model = $sth->fetch();
        return empty($model)? null: new static($model);
    }

    /**
     * Поиск моделей по атрибуту
     * @param string $table
     * @param string $attr
     * @param string $value
     * @return array
     */
    public function findAllByAttribute($table, $attr, $value, $select = '*')
    {
        $data = [];
        $models = [];      
        $sql = "SELECT $select FROM $table WHERE $attr = :attr";
        $sth = self::db()->prepare($sql);
        $sth->execute([':attr' => $value]);
        $data = $sth->fetchAll();

        if (!is_null($data)) {
            foreach ($data as $model) {
                $models[] = new static($model);
            }
        }

        return $models;
    }

    /**
     * Валидация атрибутов
     * @return type
     */
    public function validateAttr()
    {
        $validate = [];

        foreach ($this->getValidParams() as $key => $pattern) {
            if (!preg_match($pattern, $this->{$key}))
               $validate[$key] = false;
        }
        
        return empty($validate)? true: $validate;
    }
}
