<?php
require_once __DIR__ . '/htmlVersions.php';
require_once __DIR__ . '/vendor/autoload.php';
error_reporting(E_ALL ^ E_WARNING);
define('PATH_ROOT', __DIR__);
$app = new Sincco\Sfphp\App;
$app->run();
