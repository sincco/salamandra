<?php
require_once __DIR__ . '/vendor/autoload.php';

define('PATH_ROOT', __DIR__);
define('PATH_ERROR_TEMPLATE', PATH_ROOT . '/errors');
define('PATH_CACHE', PATH_ROOT . '/var/cache');
define('PATH_LOGS', PATH_ROOT . '/var/log');
define('PATH_CONFIG', PATH_ROOT . '/etc/config');
define('PATH_SESSION', PATH_ROOT . '/var/session');

use Sincco\Sfphp\Session;
use Sincco\Sfphp\Launcher;

try {
	Session::get();
	new Launcher();
}
catch (\Sincco\Sfphp\Exception $err) {
	$err->screenError($err);
}