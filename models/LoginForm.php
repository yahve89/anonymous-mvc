<?php 

namespace App\Models;

class LoginForm extends \App\Basic\Model
{
    public function __construct($model = null) 
    {
        if ($model === null)
            $model = ['username' => '', 'password' => ''];

        $this->setModel($model);
    }

    /**
     * Параметры валидации полей
     * @return array
     */
    public function getValidParams()
    {
        return [
            'username' => '/^([\S]{1,255})$/m',
            'password' => '/^([\S]{1,255})$/m'
        ];
    }
}
