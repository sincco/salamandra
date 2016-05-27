<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . 'paths.php';

use Sincco\Sfphp\Session;
use Sincco\Sfphp\Launcher;

try {
	Session::get();
	new Launcher();
}
catch (\Sincco\Sfphp\Exception $err) {
	$err->screenError($err);
}