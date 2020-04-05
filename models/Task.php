<?php 

namespace App\Models;

use \App\Basic\Session;
use \App\Models\Status;
use \Envms\FluentPDO\Query;

class Task extends \App\Basic\Model
{
    public function __construct($model = null) 
    {
        if ($model === null)
            $model = $this->table('task');

        $this->setModel($model);
    }

    /**
     * @param string $column
     * @param string $type
     * @return type
     */
    private function sort($query, $column, $type)
    {
        if (in_array($column, ['user_name', 'email', 'type']))
            switch ($type) {
                case 'desc':
                    return $query->order("$column DESC");
                    break;
                
                default:
                    return $query->order($column);
                    break;
            }

        return $query;
    }

    /**
     * Поиск пользователя по username
     * @param string $username
     * @return static|null
     */
    public function findAll($currentPage = 1, $perPage = 25, $sort = null)
    {
        $data = [];
        $models = [];
        $db = self::db();
        $sth = $db->prepare('SELECT id FROM task');
        $sth->execute();
        $totalPages = ceil($sth->rowCount() / $perPage);
        $start = ($currentPage - 1) * $perPage;
        $query = (new Query($db))
            ->from('task') 
            ->leftJoin('status ON status.task_id = task.id AND type != 2')
            ->select('status.type')
            ->limit($perPage)
            ->offset($start);

        if (!empty($sort)) {
            if (count($exp = explode('.', $sort)) === 2) {
                list($column, $type) = $exp;
                $query = $this->sort($query, $column, $type);
            }
        }

        $data = $query->fetchAll();

        if (!is_null($data)) {
            foreach ($data as $key => $model) {
                $models[$key] = new static($model);
            }
        }

        return empty($data)? []: ['models' => $models, 
            'pagination' => [
                'totalPages' => $totalPages, 
                'currentPage' => $currentPage,
                'prevPage' => $currentPage - 1,
                'nextPage' => $currentPage + 1,
                'perPage' => $perPage
            ] 
        ];
    }

    /**
     * Добавить задачу
     * @return type
     */
    public function insert()
    {
        self::db()->beginTransaction();

        try {
            $sql = 'INSERT INTO task(user_name, email, text) VALUES (:user_name, :email, :text)';
            $sth = self::db()->prepare($sql);
            $sth->execute([
                ':user_name' => $this->user_name,
                ':email' => $this->email,
                ':text' => $this->text
            ]);

            $sql = 'INSERT INTO status(task_id, type) VALUES (:task_id, :type)';
            $sth = self::db()->prepare($sql);
            $sth->execute([
                ':task_id' => self::db()->lastInsertId(),
                ':type' => 0
            ]);

            return self::db()->commit();
        } catch (Exception $e) {
            self::db()->rollBack();
            return $e;
        }
    }

    /**
     * Обновить задачу
     * @param type $name
     */
    public function update()
    {
        self::db()->beginTransaction();

        try {
            $sql = 'UPDATE `task` SET `user_name`= :user_name, `email`= :email,`text`= :text WHERE id = :id';
            $sth = self::db()->prepare($sql);
            $sth->execute([
                ':user_name' => $this->user_name,
                ':email' => $this->email,
                ':text' => $this->text,
                ':id' => $this->id
            ]);
            
            $sql = 'UPDATE `status` SET `type`= :type WHERE task_id = :task_id and type != 2';
            $sth = self::db()->prepare($sql);
            $sth->execute([
                ':type' => (int) $this->status,
                ':task_id' => $this->id
            ]);
            
            if ($this->adminEdit === true) {
                $sql = 'SELECT `id` FROM `status` WHERE task_id = :task_id';
                $sth = self::db()->prepare($sql);
                $sth->bindParam(':task_id', $this->id);  
                $sth->execute();
                
                if ($sth->rowCount([':task_id' => $this->id]) < 2) {
                    $sql = 'INSERT INTO status(task_id, type) VALUES (:task_id, :type)';
                    $sth = self::db()->prepare($sql);
                    $sth->execute([
                        ':task_id' => $this->id,
                        ':type' => 2
                    ]);
                }
            }

            return self::db()->commit();
        } catch (Exception $e) {
            self::db()->rollBack();
            return $e;
        } 
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return (new Status)
            ->findAllByAttribute('status', 'task_id', $this->id, 'id, task_id, type');
    }
    
    /**
     * @return null | object
     */
    public function getStatus()
    {
        $sql = "SELECT id, task_id, type FROM status WHERE task_id = :task_id AND type != 2";
        $sth = self::db()->prepare($sql);
        $sth->execute([':task_id' => $this->id]);
        $model = $sth->fetch();
        return empty($model)? null: new Status($model);
    }

    /**
     * Удалить запись по id
     * @param string $table
     * @param integer $id
     * @return bool
     */
    public static function delete($id)
    {
        self::db()->beginTransaction();
        
        try {
            $sql = 'DELETE FROM status WHERE task_id = :task_id';
            $sth = self::db()->prepare($sql);
            $sth->bindParam(':task_id', $id, \PDO::PARAM_INT);   
            $sth->execute();

            $sql = 'DELETE FROM task WHERE id = :id';
            $sth = self::db()->prepare($sql);
            $sth->bindParam(':id', $id, \PDO::PARAM_INT);   
            $sth->execute();

            return self::db()->commit();
        } catch (Exception $e) {
            self::db()->rollBack();
            return $e;
        }
    }

    /**
     * @param type $status
     */
    public function setStatus($status)
    {
        if (!empty($status))
            $status = htmlspecialchars($status, ENT_QUOTES);
        
        $this->status = $status;
        return $this;
    }

    /**
     * @param type $text
     */
    public function setText($text)
    {
        if (!empty($text))
            $text = htmlspecialchars($text, ENT_QUOTES);
        
        $this->text = $text;
        return $this;
    }

    /**
     * @param type $email
     */
    public function setEmail($email)
    {
        if (!empty($email))
            $email = htmlspecialchars($email, ENT_QUOTES);
        
        $this->email = $email;
        return $this;
    }

    /**
     * @param type $userName
     */
    public function setUserName($userName)
    {
        if (!empty($userName))
            $userName = htmlspecialchars($userName, ENT_QUOTES);
        
        $this->user_name = $userName;
        return $this;
    }
    
    /**
     * Параметры валидации полей
     * @return array
     */
    public function getValidParams()
    {
        return [
            'user_name' => '/^((.){3,255})$/m',
            'text' => '/(.)/m',
            'email' => '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/m'
        ];
    }
}
