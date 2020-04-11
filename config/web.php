<?php 

return [
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=mvc;charset=utf8',
        'username' => 'root',
        'password' => ''
    ],
    'route' => [
        '/^(login)$/m' => ['setAction'],
        '/^(logout)$/m' => ['setAction'],
        '/^([a-z]+)$/m' => ['setController'],
        '/^([a-z]+)\/$/m' => ['setController'],
        '/^([a-z]+)\/(\d+)$/m' => ['setController', 'id'],
        '/^([a-z]+)\/([a-z]+)$/m' => ['setController', 'setAction'],
        '/^([a-z]+)\/([a-z]+)\/(\d+)$/m' => ['setController', 'setAction', 'id']
    ],
    'alias' => [
        'rootDir' => dirname(__FILE__, 2) .DS,
        'viewDir' =>  dirname(__FILE__, 2) .DS .'views' .DS 
    ]
];