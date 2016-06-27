<?php

use \Sincco\Sfphp\Request;

/**
 * Autorizaciones con AuthKey de Google
 */
class AuthqrController extends Sincco\Sfphp\Abstracts\Controller {

	private $llave = 'GEAKO2IJW4PKLBXF';

	public function index() {
		$this->helper( 'UsersAccount' )->checkLogin();
		$view = $this->newView( 'AuthQR' );
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}

	public function apiValidar() {
		$tiempo = floor(time() / 30);
		$codigo = $this->getParam( 'codigo' );
		var_dump($codigo);die();
		if($_auth->validaCodigo($this->llave, $_codigo))
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
		$this->helper( 'UsersAccount' )->checkLogin();
		$tiempo = floor(time() / 30);
		$view = $this->newView( 'AuthQRConfig' );
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->qrUrl = $this->helper( 'Google2Step' )->urlQR( 'sae', 'suhner.com', $this->llave );
		$view->codigo = $this->helper( 'Google2Step' )->generaCodigo( $this->llave, $tiempo);
		$view->render();
	}

}