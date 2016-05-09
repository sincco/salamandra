<?php

class ClientesController extends Sincco\Sfphp\Abstracts\Controller {
//$this->nombre = 'usuarios';
	public function index() {
		$mdlClientes = $this->getModel('Catalogo\Clientes');
		$data = $mdlClientes->getAll();
		$view = $this->newView('Catalogo\ClientesTabla');
		$view->clientes = $data;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus( $data );
		echo $view->render();
	}
}