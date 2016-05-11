<?php
require_once __DIR__ . '/vendor/autoload.php';
use Sincco\Sfphp\DB\Connector;
use Sincco\Sfphp\Config\Writer;

define('DEV_CACHE', 1);
define('PATH_ROOT', __DIR__);
define('PATH_ERROR_TEMPLATE', PATH_ROOT . '/errors');
define('PATH_CACHE', PATH_ROOT . '/var/cache');
define('PATH_LOGS', PATH_ROOT . '/var/log');
define('PATH_CONFIG', PATH_ROOT . '/etc/config');
define('PATH_SESSION', PATH_ROOT . '/var/session');

$_contenido = file_get_contents("php://input");
$params = array();		
if(trim($_contenido) != "") {
	$params = json_decode($_contenido, TRUE);
}
$data = $params['instalacion'];
$bases = array( 
	'default'=>array(
		'host'=>$data['host'],
		'user'=>$data['user'],
		'password'=>$data['password'],
		'dbname'=>$data['dbname'],
		'type'=>$data['type']
	),
	'sae'=>array(
		'host'=>$data['sae_host'],
		'user'=>'sysdba',
		'password'=>'masterkey',
		'dbname'=>$data['sae_dbname'],
		'type'=>'sae'
	),
);
$db = new Connector();
$db->connectionData($data);
if( !createXML( $bases, $data['dominio'] ) ) {
	echo json_encode(array('respuesta'=>true));
	
}
try {
	$lines = file('salamandra.sql');
	$query = '';
	foreach ($lines as $line) {
		if (substr($line, 0, 2) == '--' || $line == '')
		continue;
		$query .= $line;
		if (substr(trim($line), -1, 1) == ';') {
			$db->query($query);
			$query = '';
		}
	}
	echo json_encode(array('respuesta'=>true));
} catch (\PDOException $err) {
	$errorInfo = sprintf( '%s: %s in %s on line %s.',
		'Database Error',
		$err,
		$err->getFile(),
		$err->getLine()
	);
	echo json_encode(array('respuesta'=>false, 'mensaje'=>$errorInfo));
}


function createXML($bases, $url) {
	$_llave_encripcion = strtoupper(md5(microtime().rand()));
	$_config = array (
		'app' => array (
			'key' => $_llave_encripcion,
			'name' => 'salamandra',
			'company' => 'sincco.com',
			'timezone' => 'America/Chicago',
			'version' => '0.0.1'
		),
		'front' => array (
			'url' => $url,
		),
		'bases' => $bases,
		'sesion' => array (
			'type' => "DEFAULT",
			'name' => "salamandra",
			'ssl' => 0,
			'inactivity' => 300,
		),
	);
	return Writer::write( $_config, 'config', 'etc/config/config.xml');
		chmod("./Etc/Config/config.xml", 0777);
}