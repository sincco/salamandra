<?php

use \Sincco\Sfphp\Request;

/**
 * Autorizaciones con AuthKey de Google
 */
class AuthqrController extends Sincco\Sfphp\Abstracts\Controller {

	private $llave = 'GEAKO2IJW4PKLBXF';

	public function index() {
		$llave = "GEAKO2IJW4PKLBXF";
		$tiempo = floor(time() / 30);
		$_auth = new Google2Step();
		$_codigo = $_POST['codigo'];
		if($_auth->validaCodigo($llave,$_codigo))
			$this->_vista->clientes = $this->_modelo->actualiza($_POST['cliente'],$_POST['status']);
		else
			echo "ERROR";
	}

	public function apiObtenerCodigo() {
		$tiempo = floor(time() / 30);
		$codigo = [];
		$codigo['codigo'] = $this->helper( 'Google2Step' )->generaCodigo( $this->llave, $tiempo);
		echo json_encode( $codigo );
	}

	public function config() {
		$tiempo = floor(time() / 30);
		$view = $this->newView( 'AuthQRConfig' );
		$view->qrUrl = $this->helper( 'Google2Step' )->urlQR( 'sae', 'suhner.com', $this->llave );
		$view->codigo = $this->helper( 'Google2Step' )->generaCodigo( $this->llave, $tiempo);
		$view->render();
	}

}