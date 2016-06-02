<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/paths.php';

use Sincco\Sfphp\Session;
use Sincco\Sfphp\Launcher;

try {
	Session::get();
	new Launcher();
}catch (\Exception $err) {
	$errorInfo = sprintf( '%s: %s in %s on line %s.',
		'Error',
		$err,
		$err->getFile(),
		$err->getLine()
	);
	Debug::dump( $errorInfo );
}