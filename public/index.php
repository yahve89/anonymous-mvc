<?php

require dirname(__DIR__) .'/vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);

$config = require dirname(__DIR__) . '/config/web.php';

if (file_exists($localConfig = dirname(__DIR__) . '/config/local-web.php'))
    $config = array_merge($config, require $localConfig);

\App\Basic\App::self()->setConfig($config)->run();