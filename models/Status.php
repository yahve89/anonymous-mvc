<?php 

namespace App\Models;

class Status extends \App\Basic\Model
{    
    public function __construct($model = null) 
    {
        if ($model === null)
            $model = $this->table('status');

        $this->setModel($model);
    }

    /**
     * @return type
     */
    public function getTypeName()
    {
        return [
            0 => 'не выполнено',
            1 => 'выполнено',
            2 => 'отредактировано администратором'
        ][$this->type];
    }
}
