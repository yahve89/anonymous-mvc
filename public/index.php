<?php

require dirname(__DIR__) .'/vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);

(new App\Basic\Application())->run();