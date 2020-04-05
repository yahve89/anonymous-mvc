<?php 

namespace App\Controllers;

use App\Helpers\Main;
use App\Models\Task;

class TaskController extends \App\Basic\Controller
{
    /**
     * Проверяет доступ к экшену
     * @return array
     */
    public function access()
    {
        return [
            null => ['actionCreate'],
            'root' => ['actionDelete', 'actionCreate', 'actionUpdate', 'actionTestUpdate']
        ];
    }

    /**
     * Страница создания задачи
     * @return mixed
     */
    public function actionCreate()
    {
        $validate = null;
        $model = new Task;

        if (isset($_POST['task'])) {
            $task = $_POST['task'];
           
            $model->setUserName($task['user_name']);
            $model->setEmail($task['email']);
            $model->setText($task['text']);
           
            $validate = $model->validateAttr();

            if (is_bool($validate)) {
                if ($model->insert() === true) {
                    $this->setFlash('success', 'Задание добавлено.');
                    $this->redirect('/');
                }
            }
        }

        return $this->render('task/create', [
            'title' => 'taskController',
            'model' =>  $model,
            'validate' => $validate
        ]);
    }

    /**
     * Страница обновления задачи
     * @param array $params
     * @return mixed
     */
    public function actionUpdate($params)
    {

        if (empty($params['id']))
            Main::exception('Bad request', 400);

        $data = NULL;
        $input = file_get_contents('php://input');
        $model = (new Task)->findById('task', $params['id']);
        
        if (is_null($model))
            Main::exception('Page not found', 404);

        if (isset($_POST['task']) or !empty($data = json_decode($input, true))) {
            if (!empty($data)) {
                $task = $data['task'];
            } else {
                $task = $_POST['task'];
            }

            $lastModel = clone $model;
            
            unset($lastModel->status);
            unset($lastModel->id);

            $model->setUserName($task['user_name']);
            $model->setEmail($task['email']);
            $model->setText($task['text']);
            $model->setStatus($task['status']);
            
            $validate = $model->validateAttr();
            $newModel = clone $model;            
            
            unset($task['status']);
            unset($newModel->status);
            unset($newModel->id);

            if (strcasecmp(json_encode($lastModel), json_encode($newModel)) != 0)
                $model->adminEdit = true;

            if (is_bool($validate)) {
                if ($model->update() === true) {
                    if(isset($_GET['ajax']))
                        return true; 
                    
                    $this->setFlash('success', 'Задание обновлено.');
                    $this->redirect('/');
                }
            }
        }

        return $this->render('task/update', [
            'title' => 'taskController',
            'model' => $model
        ]);
    }

    /**
     * Удаление задачи
     * @param array $params
     * @return mixed
     */
    public function actionDelete($params)
    {
        if (isset($_POST)) {
            if (!empty($params['id'])) {
                if (Task::delete($params['id']))
                    $this->setFlash('success', 'Задание удалено.');         
            }
        }
    }
}
