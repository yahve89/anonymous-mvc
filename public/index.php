<?php

require dirname(__DIR__) .'/vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);

 $config = require dirname(__DIR__) . '/config/web.php';

\App\Basic\ai::app()->setConfig($config)->run();