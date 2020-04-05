<?php 

namespace App\Controllers;

use App\Helpers\Main;
use App\Models\User;
use App\Models\Task;
use App\Models\LoginForm;

class DefaultController extends \App\Basic\Controller
{
    /**
     * Проверяет доступ к экшену
     * @return array
     */
    public function access()
    {
        return [
            null => ['actionIndex', 'actionLogin', 'actionFaq'],
            'root' => ['actionIndex', 'actionLogin', 'actionLogout', 'actionFaq']
        ];
    }

    /**
     * Главная страница
     * @return mixed
     */
    public function actionIndex()
    {   
        $page = 1;
        $perPage = 3;
        $sort = null;

        if (!empty($_GET['sort']))
            $sort = (int) $_GET['sort'];

        if (!empty($_GET['page']))
            $page = (int) $_GET['page'];

        if (!empty($_GET['sort']))
            $sort = $_GET['sort'];

        return $this->render('default/index', array_merge((new Task)->findAll($page, $perPage, $sort), 
            ['title' => 'DefaultController'])
        );
    }

    /**
     * Статичная страница
     * @return mixed
     */
    public function actionFaq()
    {  
        return $this->render('default/faq', ['title' => 'DefaultController']);
    }

    /**
     * Начало сессии пользователя
     * @param array $params
     * @return mixed
     */
    public function actionLogin()
    {
        $validate = null;
        $loginForm = new LoginForm;

        if (isset($_POST['auth'])) {
            $auth = $_POST['auth'];

            $loginForm->username = trim($auth['username']);
            $loginForm->password = trim($auth['password']);

            $validate = $loginForm->validateAttr();
            
            if (empty($user = User::findByUsername($loginForm->username)))
                $validate = false;

            if ($validate === true) {  
                if ($validate = $user->validatePassword($loginForm->password)) {
                    self::$session->identity = $user->id;
                    $this->redirect('/');
                }
            }
        }

        return $this->render('default/login', [
            'title' => 'DefaultController',
            'loginForm' =>  $loginForm,
            'validate' => $validate
        ]);
    }

    /**
     *  Конец сессии пользователя
     * @return mixed
     */
    public function actionLogout()
    {
        self::$session->destroy();
        $this->redirect('/');
    }
    
}
